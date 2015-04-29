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
            text_dollar($test);
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
    $sum = fix_input($sum);
    $tokens = parse_number($sum);
    $answer = compose_phrase($tokens);
    echo $answer . "\n";
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

}

/**
 * Maps round numbers to words
 *
 * @param int|string $num Round number from 20 to 90
 * @return string English verbal representation of the given number
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
}

/**
 * Splits positive integer on tokens, designed for verbal conversion
 *
 * @param $num Number to be split on tokens
 * @return array Tokens prepared to words substitution
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
 * Composes english verbal representation of positive integer
 *
 * @param int[] $tokens Given number split to parts
 * @return string Verbal representation of given number
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

    // OneDollar Case
    if($last_index == 0 && $tokens[0] == 1) {
        return 'OneDollar';
    }

    while ($ptr <= $last_index) {

        array_push($result, $scale[$round]);
        // Add 1 - 19
        if ($tokens[$ptr] > 0) {
            array_push($result, map_0_to_19($tokens[$ptr++]));
        } else {
            $ptr++;
        }

        // Add 20 - 90
        if ($ptr <= $last_index && $tokens[$ptr] > 9) {
            array_push($result, map_20_to_90($tokens[$ptr++]));
        }

        // Add hundreds
        if ($ptr <= $last_index && $tokens[$ptr] > 0) {
            array_push($result, map_0_to_19($tokens[$ptr++]) . 'Hundred');
        } else {
            $ptr++;
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