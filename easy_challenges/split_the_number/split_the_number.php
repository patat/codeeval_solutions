<?php
$fh = fopen($argv[1], "r");
while (!feof($fh)) {
  $test = fgets($fh);
  if (!empty($test)) {
    $parts = explode( ' ', $test );
    if ( false !== stripos( $parts[1], '+' ) ) {
      $operands = explode( '+', $parts[1] );
      $operand1 = substr( $parts[0], 0, strlen( $operands[0] ) );
      $operand2 = substr( $parts[0], strlen( $operands[0] ) );
      $result = intval( $operand1 ) + intval( $operand2 );
    } else {
      $operands = explode( '-', $parts[1] );
      $operand1 = substr( $parts[0], 0, strlen( $operands[0] ) );
      $operand2 = substr( $parts[0], strlen( $operands[0] ) );
      $result = intval( $operand1 ) - intval( $operand2 );
    }

    echo "$result\n";
  }
}
fclose($fh);