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

//
try {
    // test 1
    $answer = '###########\\ /\\/X\\ ## X /* \\\\##/*X/ \\ X##\\//\\* X/##/X *\\//\\##X \\ /**/##\\\\ X/** ## \\X/\\/ \\####X######';
    $test = '###########        ##    *   ## *      ##    *   ##   *    ##     ** ##     ** ##        ####/######';
    $room = new Room($test, 10);
    $new_test = $room->serialize();
    assert($test == $new_test);
    $room->print_room();
    //$room->distribute_light();
    $result = $room->serialize();
    assert($result == $answer);

    // test 2
    $answer = '###########  /\\ /\\ ## /  * o\\##/ o/ \\ /#/  / o X ## o * / \\##/ \\ /  /##\\  X  / ## \\/ \\/  ###########';
    $test = '###########        ##    * o ##  o     #/    o   ## o *    ##       *##        ##        ###########';
    $room = new Room($test, 10);
    $new_test = $room->serialize();
    //assert($test == $new_test);
    $room->print_room();
    $room->distribute_light();
    $room->print_room();
    $result = $room->serialize();
    //assert($result == $answer);

} catch (Exception $ex) {
    echo $ex->getMessage() . "\n";
    echo "In " . $ex->getFile() . ", line: " . $ex->getLine() . "\n";
    echo $ex->getTraceAsString() . "\n";
}
