<?php
/**
 * Script Name: Codeeval Text Dollar Solution
 * Description: My solution to Text Dollar challenge on codeeval.com
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
        while (!feof($fh)) {
            $test = fgets($fh);
            if ($test == null) {
                break;
            }
            // solution goes here

        }

        fclose($fh);
    } catch(Exception $ex){
        echo $ex->getMessage() . "\n";
        echo $ex->getCode() . "\n";
        echo $ex->getTraceAsString() . "\n";
    }

}

file_reader($argv[1]);

function text_dollar($sum) {
    //$sum = fix_input($sum);
}

/**
 * Performs type casting to int
 *
 * @param string|int $input
 * @return int
 */
function fix_input($input) {;
    return (int)$input;
}

/**
 * Maps number representation from decimal to verbal
 *
 * @param string|int $num Number from 0 to 19
 * @return string English verbal representation of the given number
 */
function map_0_to_19($num) {
    $map_0_to_19 = array(
        'Zero',
        'One',
        'Two',
        'Three',
        'Four',
        'Five',
        'Six',
        'Seven',
        'Eight',
        'Nine',
        'Ten',
        'Eleven',
        'Twelve',
        'Thirteen',
        'Fourteen',
        'Fifteen',
        'Sixteen',
        'Seventeen',
        'Eighteen',
        'Nineteen'
    );

    return $map_0_to_19[(int)$num];
}