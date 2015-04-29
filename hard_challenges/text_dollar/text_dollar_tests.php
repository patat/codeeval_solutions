<?php
/*
 * Module testing for text_dollar.php
 */
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

// parse_number() followed be compose_number() tests
$arr = parse_number(1);
assert('$arr[0] == 1');
$answer = compose_phrase($arr);
assert('$answer == "OneDollar"');

$arr = parse_number(123);
assert('$arr[0] == 3');
assert('$arr[1] == 20');
assert('$arr[2] == 1');
$answer = compose_phrase($arr);
assert('$answer == "OneHundredTwentyThreeDollars"');

$arr = parse_number(1111);
assert('$arr[0] == 11');
assert('$arr[1] == 1');
assert('$arr[2] == 1');
$answer = compose_phrase($arr);
assert('$answer == "OneThousandOneHundredElevenDollars"');

$arr = parse_number(11111);
assert('$arr[0] == 11');
assert('$arr[1] == 1');
assert('$arr[2] == 11');
$answer = compose_phrase($arr);
assert('$answer == "ElevenThousandOneHundredElevenDollars"');

$arr = parse_number(100110);
assert('$arr[0] == 10');
assert('$arr[1] == 1');
assert('$arr[2] == 0');
assert('$arr[3] == 1');
$answer = compose_phrase($arr);
assert('$answer == "OneHundredThousandOneHundredTenDollars"');