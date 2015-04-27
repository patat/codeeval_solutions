<?php
/**
 * Script Name: Codeeval Prefix Expression Solution
 * Description: My solution to Prefix Expression challenge on codeeval.com
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

            // solution goes here
            echo prefix_expression($test) . "\n";

        }

        fclose($fh);
    } catch(Exception $ex){
        echo $ex->getMessage() . "\n";
        echo $ex->getCode() . "\n";
        echo $ex->getTraceAsString() . "\n";
    }

}

file_reader($argv[1]);

/**
 * Solution wrapper
 *
 * @param string $line Space delimited prefix expression, contains operators +, -, *, /
 * @return int|float Prefix expression evaluation result
 */
function prefix_expression($line) {
    $tokens = fix_tokens($line);
    $result = eval_prefix($tokens);
    return $result;
}

/**
 * Converts string to an array of tokens, strips off whitespace
 *
 * @param string $line Space delimited prefix expression, contains operators +, -, *, /
 * @return array<string> $input Prefix expression tokens
 */
function fix_tokens($line) {
    $tokens = explode(' ', $line);
    foreach($tokens as &$token) {
        $token = trim($token);
    }
    unset($token);

    return $tokens;
}

/**
 * Evaluates prefix expression using array as a stack
 *
 * @param array $tokens Prefix expression tokens: numbers and operators
 * @return int|float Prefix expression evaluation result
 */
function eval_prefix($tokens) {
    $stack = array();
    $last_index = count($tokens) - 1;

    for($i = $last_index; $i >= 0; $i--) {
        $operator_num = match_operator($tokens[$i]);

        if ($operator_num == 0) {
            // not an operator
            array_push($stack, $tokens[$i]);
        } else {
            $operand1 = array_pop($stack);
            $operand2 = array_pop($stack);
            $res = perform_operation($operator_num, $operand1, $operand2);
            array_push($stack, $res);
        }
    }

    $result = array_pop($stack);
    return $result;
}

/**
 * Determines whether given string is defined operator and maps it to positive integer
 * if it is the case.
 *
 * @param string $operator Might be operator
 * @return int Operator number > 0 if matched, 0 otherwise
 */
function match_operator($operator) {
    switch ($operator) {
        case '+':
            return 1;
        case '-':
            return 2;
        case '*':
            return 3;
        case '/':
            return 4;
        default:
            return 0;
    }
}

/**
 * Performs defined binary operation
 *
 * @param int $operator_num Number of the defined operator > 0
 * @param string|int|float $operand1 Left operand
 * @param string|int|float $operand2 Right operand
 * @return int|float Result of the performed operation
 * @throws Exception Undefined operator
 */
function perform_operation($operator_num, $operand1, $operand2) {
    // some type casting to ensure numeric types
    $operand1 = ($operand1 === (int)$operand1) ? (int)$operand1 : (double)$operand1;
    $operand2 = ($operand2 === (int)$operand2) ? (int)$operand2 : (double)$operand2;

    switch ($operator_num) {
        case 1:
            return $operand1 + $operand2;
        case 2:
            return $operand1 - $operand2;
        case 3:
            return $operand1 * $operand2;
        case 4:
            return $operand1 / $operand2;
        default:
            throw new Exception(utf8_encode('Undefined operator'));
    }
}

/**
 * Testing section
 */
/*
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);

function tt_assert_handler($file, $line, $code, $desc = null) {
    echo "Assertion failed in $file:$line: $code";
    if ($desc) {
        echo ": $desc";
    }
    echo "\n";
}

assert_options(ASSERT_CALLBACK, 'tt_assert_handler');

// prefix_expression() test
assert('prefix_expression("/ - * + 2 3 4 5 3") == 5');
*/