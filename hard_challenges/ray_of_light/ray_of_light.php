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
    const LAST = 9;
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
    private function set_x($x) {
        if ((int)$x >= 0 && (int)$x <= self::LAST) {
            $this->x = (int)$x;
        } else {
            throw new Exception("Incorrect cell index x = $x");
        }
    }
    public function get_y() {
        return $this->y;
    }
    private function set_y($y) {
        if ((int)$y >= 0 && (int)$y <= self::LAST) {
            $this->y = (int)$y;
        } else {
            throw new Exception("Incorrect cell index y = $y");
        }
    }
    public function  get_content() {
        return $this->content;
    }
    public  function set_content($char) {
        $this->content = $char;
    }

    public function is_left() {
        return $this->x == 0;
    }
    public function is_top() {
        return $this->y == 0;
    }
    public function is_right() {
        return $this->x == self::LAST;
    }
    public function is_bottom() {
        return $this->y == self::LAST;
    }
}// Cell

class Room {
    private $home;
    private $source;

    public function __construct($serialized_room, $size) {
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

        }
    }// distribute_light()

    private function get_direction($cell) {
        if (!(method_exists($cell, 'get_content')
            && method_exists($cell, 'is_top')
            && method_exists($cell, 'is_right')
            && method_exists($cell, 'is_left')
            && method_exists($cell, 'is_bottom')
            && method_exists($cell, 'get_x')
            && method_exists($cell, 'get_y'))) {

            throw new Exception(utf8_encode("Argument is not of the Cell type."));
        }

        if ($cell->get_content() ==  '/' )
        {
            if (!$cell->is_top() && !$cell->is_right()) {
                $target = $this->home[$cell->get_x() + 1][$cell->get_y() + 1];
                if (!method_exists($target, 'get_content')) {
                    throw new Exception(utf8_encode("Target var is not of the Cell type."));
                }
                $t_content = $target->get_content();
                if ($t_content != '/') {
                    if ($t_content == '*') {

                    } else {
                        return 'upright';
                    }
                }
            }
        }
    }// get_direction()
}// Room



/*
 * Testing section
 */

require('ray_of_light_tests.php');