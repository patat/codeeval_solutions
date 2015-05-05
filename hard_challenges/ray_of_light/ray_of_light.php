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

}// Cell

class Room {
    private $home;
    private $source;
    private $size;

    public function __construct($serialized_room, $size) {
        $this->size = $size;
        $this->deserialize($serialized_room, $size);
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
                    throw new Exception(utf8_encode("Required method doesn't exist"));
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
        $stack = array();
        array_push($stack, $this->source);
        while (count($stack) > 0) {
            $ray = array_pop($stack);
            if ($ray->get_content() == '/') {
                // going up
                $target = $this->get_cell($ray->get_x() - 1, $ray->get_y() + 1);

                if ($target != null) {
                    switch ($target->get_content()) {
                        case ' ':
                            $target->set_content('/');
                            array_push($stack, $target);
                            $this->set_cell($target);
                            break;
                        case '#':
                            $target1 = $this->get_cell($target->get_x()+1, $target->get_y());
                            if ($target1 != null) {
                                switch ($target1->get_content()) {
                                    case ' ':
                                        $target1->set_content('\\');
                                        array_push($stack, $target1);
                                        $this->set_cell($target1);
                                        break;
                                    case '#':
                                        $target2 = $this->get_cell($target->get_x(), $target->get_y()-1);
                                        if ($target2 != null) {
                                            switch ($target2->get_content()) {
                                                case ' ':
                                                    $target2->set_content('\\');
                                                    array_push($stack, $target2);
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
                        default: break;
                    }
                }

                // going down
                $target = $this->get_cell($ray->get_x() + 1, $ray->get_y() - 1);
                if ($target != null) {
                    switch ($target->get_content()) {
                        case ' ':
                            $target->set_content('/');
                            array_push($stack, $target);
                            $this->set_cell($target);
                            break;
                        case '#':
                            $target1 = $this->get_cell($target->get_x() - 1, $target->get_y());
                            if ($target1 != null) {
                                switch ($target1->get_content()) {
                                    case ' ':
                                        $target1->set_content('\\');
                                        array_push($stack, $target1);
                                        $this->set_cell($target1);
                                        break;
                                    case '#':
                                        $target2 = $this->get_cell($target->get_x(), $target->get_y() + 1);
                                        if ($target2 != null) {
                                            switch ($target2->get_content()) {
                                                case ' ':
                                                    $target2->set_content('\\');
                                                    array_push($stack, $target2);
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
                        default: break;
                    }
                }

            } else if ($ray->get_content() == '\\') {
                $target = $this->get_cell($ray->get_x() - 1, $ray->get_y() - 1);

                if ($target != null) {
                    switch ($target->get_content()) {
                        case ' ':
                            $target->set_content('\\');
                            array_push($stack, $target);
                            $this->set_cell($target);
                            break;
                        case '#':
                            $target1 = $this->get_cell($target->get_x()+1, $target->get_y());
                            if ($target1 != null) {
                                switch ($target1->get_content()) {
                                    case ' ':
                                        $target1->set_content('/');
                                        array_push($stack, $target1);
                                        $this->set_cell($target1);
                                        break;
                                    case '#':
                                        $target2 = $this->get_cell($target->get_x(), $target->get_y()+1);
                                        if ($target2 != null) {
                                            switch ($target2->get_content()) {
                                                case ' ':
                                                    $target2->set_content('/');
                                                    array_push($stack, $target2);
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
                        default: break;
                    }
                }


            } else if ($ray == 'X') {

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


}// Room



/*
 * Testing section
 */

require('ray_of_light_tests.php');