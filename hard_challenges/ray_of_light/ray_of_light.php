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



/*
 * Testing section
 */

//require('ray_of_light_tests.php');