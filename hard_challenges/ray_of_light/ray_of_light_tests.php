<?php
/*
 * Module testing for ray_of_light.php
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