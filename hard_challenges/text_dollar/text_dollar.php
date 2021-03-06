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
            echo text_dollar($test);

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
 * @param string|int $sum
 * @return string English textual representation of positive integer
 * @throws Exception
 */
function text_dollar($sum) {
    $sum = fix_input($sum);
    $tokens = parse_number($sum);
    $answer = compose_phrase($tokens);
    return $answer;
}// text_dollar()

/**
 * Performs type casting to int
 *
 * @param string|int $input
 * @return int
 */
function fix_input($input) {;
    return (int)$input;
}// fix_input()

/**
 * Maps number representation from decimal to verbal
 *
 * @param string|int $num Number from 0 to 19
 * @return string English verbal representation of the given number
 * @throws Exception No name defined
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
    $num = (int)$num;
    if ($num >= 0 && $num < 20)
    {
        return $map_0_to_19[$num];
    } else {
        throw new Exception(utf8_encode("No name for $num defined."));
    }

}// map_1_to_19()

/**
 * Maps round numbers to words
 *
 * @param int|string $num Round number from 20 to 90
 * @return string English textual representation of the given number
 * @throws Exception No name defined
 */
function map_20_to_90($num) {

    $map_20_to_90 = array(
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety'
    );

    $num = (int)$num;
    if ($num >= 20 && $num <= 90 && $num % 10 == 0) {
        return $map_20_to_90[$num];
    } else {
        throw new Exception(utf8_encode("No name for $num defined."));
    }
}// map_20_to_90()

/**
 * Splits positive integer on tokens, designed for textual conversion
 *
 * @param $num Number to be split on tokens
 * @return array Tokens prepared for word substitution
 */
function parse_number($num) {
    $tokens = array();
    while ($num > 0) {
        $token99 = $num % 100;
        if ($token99 >= 0 && $token99 < 20) {
            array_push($tokens, $token99);
            $num = ($num - $token99) / 100;
        } else {
            $token9 = $num % 10;
            array_push($tokens, $token9);

            $num = $num - $token9;
            if ($num == 0)
            {
                return $tokens;
            }
            $token90 = $num % 100;
            array_push($tokens, $token90);
            $num = ($num - $token90) / 100;
        }

        if ($num == 0)
        {
            return $tokens;
        }

        $token900 = $num % 10;
        array_push($tokens, $token900);
        $num = ($num - $token900) / 10;
    }


    return $tokens;
}// parse_number()

/**
 * Composes english textual representation of positive integer
 *
 * @param int[] $tokens Given number split to parts
 * @return string Textual representation of given number
 * @throws Exception No tokens to process
 */
function compose_phrase($tokens) {
    $last_index = count($tokens) - 1;
    if ($last_index < 0) {
        throw new Exception("No tokens to process");
    }
    $scale = array(
        '',
        'Thousand',
        'Million'
    );

    $result = array();
    $ptr = 0;
    $round = 0;

    while ($ptr <= $last_index) {

        array_push($result, $scale[$round]);

        $round_fulfilled = false;
        // Add 1 - 19
        if ($tokens[$ptr] > 0) {
            array_push($result, map_0_to_19($tokens[$ptr++]));
            $round_fulfilled = true;
        } else {
            $ptr++;
        }

        // Add 20 - 90
        if ($ptr <= $last_index && $tokens[$ptr] > 9) {
            array_push($result, map_20_to_90($tokens[$ptr++]));
            $round_fulfilled = true;
        }

        // Add hundreds
        if ($ptr <= $last_index && $tokens[$ptr] > 0) {
            array_push($result, map_0_to_19($tokens[$ptr++]) . 'Hundred');
            $round_fulfilled = true;
        } else {
            $ptr++;
        }
        // Delete 10^3 name (round) if all tokens were 0
        if ($round_fulfilled == false) {
            array_pop($result);
        }
        $round++;
    }

    $result = array_reverse($result);
    $answer = implode($result) . 'Dollars';
    return $answer;
}// compose_phrase()

/*
 * Testing section
 */

//require('text_dollar_tests.php');