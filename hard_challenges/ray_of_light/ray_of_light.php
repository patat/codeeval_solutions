<?php
/**
 * Script Name: Codeeval Ray Of Light Solution
 * Description: My solution to the Ray Of Light codeeval.com challenge
 * Author: Patat
 * Author URI: https://github.com/patat
 * Version: 1.0.0
 * License: GPL2
 */

/**
 * Iterates through lines of the test file and
 * applies solution function to each line
 *
 * @param string $file_name Name of the test file to read
 */
function file_reader($file_name) {
    try {
        $fh = fopen($file_name, 'r');
        $new_line = false;
        while (!feof($fh)) {
            $test = fgets($fh);
            if ($test == null) {
                break;
            }

            if ($new_line) {
                echo "\n";
            }
            $new_line = true;
            // solution goes here


        }

        fclose($fh);
    } catch(Exception $ex){
        echo $ex->getMessage() . "\n";
        echo $ex->getCode() . "\n";
        echo $ex->getTraceAsString() . "\n";
    }

}// file_reader()

file_reader($argv[1]);

/**
 * Solution wrapper
 *
 * @param string $room
 * @return string English textual representation of positive integer
 * @throws Exception
 */
function ray_of_light($room) {

    //return $answer;
}// text_dollar()

class Cell {
    private $x;
    private $y;
    private $content;

    public function  __construct($x, $y, $content) {
        $this->set_x($x);
        $this->set_y($y);
        $this->set_content($content);
    }

    public function get_x() {
        return $this->x;
    }
    public function set_x($x) {
        $this->x = (int)$x;
    }
    public function get_y() {
        return $this->y;
    }
    private function set_y($y) {
        $this->y = (int)$y;
    }
    public function  get_content() {
        return $this->content;
    }
    public function set_content($char) {
        $this->content = $char;
    }

    public function is_equal($cell) {
        return $this->get_x() == $cell->get_x() && $this->get_y() == $cell->get_y();
    }

    public function is_light() {
        return $this->get_content() == '/' || $this->get_content() == '\\' || $this->get_content() == 'X';
    }

}// Cell

class Room {
    private $home;
    private $source;
    private $size;
    private $stack;

    public function __construct($serialized_room, $size) {
        $this->size = $size;
        $this->deserialize($serialized_room, $size);
        $this->stack = array();
    }

    /**
     * @param $serialized_room
     * @param $size
     * @throws Exception
     */
    private function deserialize($serialized_room, $size) {

        $room_length = strlen($serialized_room);
        if ($room_length / $size != $size) {
            throw new Exception(utf8_encode("Serialized data and row size don't match"));
        }
        $this->home = array();

        for($i = 0; $i < $size; $i++) {
            $row = substr($serialized_room, $i*$size, $size);
            $tmp = array();
            for($j = 0; $j < $size; $j++) {
                array_push($tmp, new Cell($i, $j, $row[$j]));
                if ($row[$j] == '/' || $row[$j] == '\\'){
                    $this->source = end($tmp);
                }
            }
            array_push($this->home, $tmp);
        }
    }// deserialize()

    /**
     * @return string Serialized room
     * @throws Exception Required method doesn't exist
     */
    public function serialize() {
        $serialized_room = '';
        foreach($this->home as $row) {
            foreach($row as $cell) {
                if (method_exists($cell, 'get_content')) {
                    $serialized_room .= $cell->get_content();
                } else {
                    $message = "Required method doesn't exist";
                    throw new Exception(utf8_encode($message));
                }

            }
        }

        return $serialized_room;
    }// serialize()

    public function print_room() {
        foreach($this->home as $row) {
            foreach($row as $cell) {
                if (method_exists($cell, 'get_content')) {
                    echo $cell->get_content();
                } else {
                    throw new Exception(utf8_encode("Required method doesn't exist"));
                }
            }
            echo "\n";
        }
    }// print_room()

    public function distribute_light() {
        $this->stack = array();
        array_push($this->stack, $this->source);
        while (count($this->stack) > 0) {
            $ray = array_pop($this->stack);
            if ($ray->get_content() == '/') {
                // going up
                $target = $this->get_cell($ray->get_x() - 1, $ray->get_y() + 1);

                if ($target != null) {
                    switch ($target->get_content()) {
                        case ' ':
                            $target->set_content('/');
                            array_push($this->stack, $target);
                            $this->set_cell($target);
                            break;
                        case '\\':
                            $target->set_content('X');
                            array_push($this->stack, $target);
                            $this->set_cell($target);
                            break;
                        case '#':
                            $target1 = $this->get_cell($target->get_x()+1, $target->get_y());
                            if ($target1 != null) {
                                switch ($target1->get_content()) {
                                    case ' ':
                                        $target1->set_content('\\');
                                        array_push($this->stack, $target1);
                                        $this->set_cell($target1);
                                        break;
                                    case '#':
                                        $target2 = $this->get_cell($target->get_x(), $target->get_y()-1);
                                        if ($target2 != null) {
                                            switch ($target2->get_content()) {
                                                case ' ':
                                                    $target2->set_content('\\');
                                                    array_push($this->stack, $target2);
                                                    $this->set_cell($target2);
                                                    break;
                                                default: break;
                                            }
                                        }
                                        break;
                                    case 'o': break;
                                    default: break;
                                }
                            }
                        case 'o':
                            break;
                        case '*':
                            $this->prism_distribution($ray, $target);

                            break;
                        default: break;
                    }
                }

                // going down
                $target = $this->get_cell($ray->get_x() + 1, $ray->get_y() - 1);
                if ($target != null) {
                    switch ($target->get_content()) {
                        case ' ':
                            $target->set_content('/');
                            array_push($this->stack, $target);
                            $this->set_cell($target);
                            break;
                        case '\\':
                            $target->set_content('X');
                            array_push($this->stack, $target);
                            $this->set_cell($target);
                            break;
                        case '#':
                            $target1 = $this->get_cell($target->get_x() - 1, $target->get_y());
                            if ($target1 != null) {
                                switch ($target1->get_content()) {
                                    case ' ':
                                        $target1->set_content('\\');
                                        array_push($this->stack, $target1);
                                        $this->set_cell($target1);
                                        break;
                                    case '#':
                                        $target2 = $this->get_cell($target->get_x(), $target->get_y() + 1);
                                        if ($target2 != null) {
                                            switch ($target2->get_content()) {
                                                case ' ':
                                                    $target2->set_content('\\');
                                                    array_push($this->stack, $target2);
                                                    $this->set_cell($target2);
                                                    break;
                                                default: break;
                                            }
                                        }
                                        break;
                                    case 'o': break;
                                    default: break;
                                }
                            }
                        case 'o':
                            break;
                        case '*':
                            $this->prism_distribution($ray, $target);

                            break;
                        default: break;
                    }
                }

            } else if ($ray->get_content() == '\\') {
                // going up
                $target = $this->get_cell($ray->get_x() - 1, $ray->get_y() - 1);

                if ($target != null) {
                    switch ($target->get_content()) {
                        case ' ':
                            $target->set_content('\\');
                            array_push($this->stack, $target);
                            $this->set_cell($target);
                            break;
                        case '/':
                            $target->set_content('X');
                            array_push($this->stack, $target);
                            $this->set_cell($target);
                            break;
                        case '#':
                            $target1 = $this->get_cell($target->get_x()+1, $target->get_y());
                            if ($target1 != null) {
                                switch ($target1->get_content()) {
                                    case ' ':
                                        $target1->set_content('/');
                                        array_push($this->stack, $target1);
                                        $this->set_cell($target1);
                                        break;
                                    case '#':
                                        $target2 = $this->get_cell($target->get_x(), $target->get_y()+1);
                                        if ($target2 != null) {
                                            switch ($target2->get_content()) {
                                                case ' ':
                                                    $target2->set_content('/');
                                                    array_push($this->stack, $target2);
                                                    $this->set_cell($target2);
                                                    break;
                                                default: break;
                                            }
                                        }
                                        break;
                                    case 'o': break;
                                    default: break;
                                }
                            }
                        case 'o':
                            break;
                        case '*':
                            $this->prism_distribution($ray, $target);

                            break;
                        default: break;
                    }
                }

                // going down
                $target = $this->get_cell($ray->get_x() + 1, $ray->get_y() + 1);

                if ($target != null) {
                    switch ($target->get_content()) {
                        case ' ':
                            $target->set_content('\\');
                            array_push($this->stack, $target);
                            $this->set_cell($target);
                            break;
                        case '/':
                            $target->set_content('X');
                            array_push($this->stack, $target);
                            $this->set_cell($target);
                            break;
                        case '#':
                            $target1 = $this->get_cell($target->get_x()-1, $target->get_y());
                            if ($target1 != null) {
                                switch ($target1->get_content()) {
                                    case ' ':
                                        $target1->set_content('/');
                                        array_push($this->stack, $target1);
                                        $this->set_cell($target1);
                                        break;
                                    case '#':
                                        $target2 = $this->get_cell($target->get_x(), $target->get_y() - 1);
                                        if ($target2 != null) {
                                            switch ($target2->get_content()) {
                                                case ' ':
                                                    $target2->set_content('/');
                                                    array_push($this->stack, $target2);
                                                    $this->set_cell($target2);
                                                    break;
                                                default: break;
                                            }
                                        }
                                        break;
                                    case 'o': break;
                                    default: break;
                                }
                            }
                        case 'o':
                            break;
                        case '*':
                            $this->prism_distribution($ray, $target);
                            break;
                        default: break;
                    }
                }

            } else if ($ray->get_content() == 'X') {
                // choose target
                $target = $this->get_cell($ray->get_x() + 1, $ray->get_y() + 1);
                if ($target != null && $target->is_light() ) {
                    $target = $this->get_cell($ray->get_x() + 1, $ray->get_y() - 1);
                    if ($target != null && $target->is_light()) {
                        $target = $this->get_cell($ray->get_x() - 1, $ray->get_y() - 1);
                        if($target != null && $target->is_light) {
                            $target = $this->get_cell($ray->get_x() - 1, $ray->get_y() + 1);
                        }
                    }
                }


            } else {
                //exc
            }
            // test
            //$this->print_room();
        }
    }// distribute_light()

    private function cell_exists($x, $y) {
        $cond = $x >= 0 && $x < $this->size && $y >=0 && $y < $this->size;
        return $cond;
    }// cell_exists()

    private function get_cell($x, $y) {
        return ($this->cell_exists($x, $y)) ? $this->home[$x][$y] : null;
    }// get_cell()
    private function set_cell($cell) {
        if ($this->cell_exists($cell->get_x(), $cell->get_y())) {
            $this->home[$cell->get_x()][$cell->get_y()] = $cell;
        } else {
            throw new Exception(utf8_encode("Cell is out of borders."));
        }
    }// set_cell()

    private function prism_distribution($ray, $target) {
        $target1 = $this->get_cell($target->get_x() - 1, $target->get_y() + 1);
        $target2 = $this->get_cell($target->get_x() + 1, $target->get_y() + 1);
        $target3 = $this->get_cell($target->get_x() + 1, $target->get_y() - 1);
        $target4 = $this->get_cell($target->get_x() - 1, $target->get_y() - 1);
        // north-east from the target
        if ($target1 != null && !$target1->is_equal($ray)) {
            switch ($target1->get_content()) {
                case ' ':
                    $target1->set_content('/');
                    array_push($this->stack, $target1);
                    $this->set_cell($target1);
                    break;
                case '\\':
                    $target1->set_content('X');
                    array_push($this->stack, $target1);
                    $this->set_cell($target1);
                    break;
                case '*':
                    $this->prism_distribution($target, $target1);
                    break;
                default:
                    break;
            }
        }
        // south-east from the target
        if ($target2 != null && !$target2->is_equal($ray)) {
            switch ($target2->get_content()) {
                case ' ':
                    $target2->set_content('\\');
                    array_push($this->stack, $target2);
                    $this->set_cell($target2);
                    break;
                case '*':
                    $this->prism_distribution($target, $target2);
                    break;
                case '/':
                    $target1->set_content('X');
                    array_push($this->stack, $target1);
                    $this->set_cell($target1);
                    break;
                default:
                    break;
            }
        }
        // south-west from the target
        if ($target3 != null && !$target3->is_equal($ray)) {
            switch ($target3->get_content()) {
                case ' ':
                    $target3->set_content('/');
                    array_push($this->stack, $target3);
                    $this->set_cell($target3);
                    break;
                case '*':
                    $this->prism_distribution($target, $target3);
                    break;
                case '\\':
                    $target1->set_content('X');
                    array_push($this->stack, $target1);
                    $this->set_cell($target1);
                    break;
                default:
                    break;
            }
        }
        // north-west from target
        if ($target4 != null && !$target4->is_equal($ray)) {
            switch ($target4->get_content()) {
                case ' ':
                    $target4->set_content('\\');
                    array_push($this->stack, $target4);
                    $this->set_cell($target4);
                    break;
                case '*':
                    $this->prism_distribution($target, $target4);
                    break;
                case '/':
                    $target1->set_content('X');
                    array_push($this->stack, $target1);
                    $this->set_cell($target1);
                    break;
                default:
                    break;
            }
        }
    }// prism_distribution()

    private function revert_ray($char) {
        return ($char == '/') ? '\\' : '/';
    }// revert_ray()

    private function revert_num($num) {
        return (-$num);
    }// revert_num()

    function explore_target($ray, $dx, $dy) {
        $target = $this->get_cell($ray->get_x() + $dx, $ray->get_y() + $dy);

        $reverted_ray = $this->revert_ray($ray->get_content);
        if ($target != null) {
            switch ($target->get_content()) {
                case ' ':
                    $this->advance_ray($target, $ray->get_content());
                    break;
                case $reverted_ray:
                    $this->advance_ray($target, 'X');
                    break;
                case '#':
                    $target1 = $this->get_cell($target->get_x() + $this->revert_num($dx), $target->get_y());
                    if ($target1 != null) {
                        switch ($target1->get_content()) {
                            case ' ':
                                $this->advance_ray($target1, $reverted_ray);
                                break;
                            case '#':
                                $target2 = $this->get_cell($target->get_x(), $target->get_y() + $this->revert_num($dy));
                                if ($target2 != null) {
                                    switch ($target2->get_content()) {
                                        case ' ':
                                            $this->advance_ray($target2, $reverted_ray);
                                            break;
                                        default: break;
                                    }
                                }
                                break;
                            case 'o': break;
                            default: break;
                        }
                    }
                case 'o':
                    break;
                case '*':
                    $this->prism_distribution($ray, $target);
                    break;
                default: break;
            }
        }
    }

    private function advance_ray($target, $new_content) {
        $target->set_content($new_content);
        array_push($this->stack, $target);
        $this->set_cell($target);
    }
}// Room



/*
 * Testing section
 */

require('ray_of_light_tests.php');