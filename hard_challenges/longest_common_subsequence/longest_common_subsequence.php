<?php
/**
 * Script Name: Codeeval Longest Common Subsequence Solution
 * Description: My solution to Longest Common Subsequence challenge on codeeval.com
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
function file_reader( $file_name ) {
    try {
        $fh = fopen( $file_name, 'r' );
        $new_line = false;
        while ( ! feof($fh) ) {
            $test = fgets( $fh );
            if ( $test == null ) {
                break;
            }

            if ( $new_line ) {
                echo "\n";
            }
            $new_line = true;
            // solution goes here
            echo longest_common_subsequence( $test );

        }

        fclose($fh);
    } catch(Exception $ex){
        echo $ex->getMessage() . "\n";
        echo $ex->getCode() . "\n";
        echo $ex->getTraceAsString() . "\n";
    }

}// file_reader()

file_reader( $argv[1] );

/**
 * Solution wrapper
 *
 * @param  string    $test_input   Two sequences semicolon delimited
 * @return string    Longest common subsequence
 * @throws Exception
 */

function longest_common_subsequence( $test_input ) {

  $sequences = explode( ';', $test_input );
  $row_sequence = $sequences[0];
  $col_sequence = $sequences[1];

  $seq_table = array();

  $ii = strlen( $row_sequence );
  $jj = strlen( $col_sequence );

  for( $i = 0; $i <= $ii; $i++ ) {
    $seq_row = array();
    for( $j = 0; $j <= $jj; $j++ ) {
      $seq_row[] = 0;
    }
    $seq_table[] = $seq_row;
  }

  // start filling the sequence table

  for( $i = 1; $i <= $ii; $i++ ) {
    for ( $j = 1; $j <= $jj; $j++ ) {
      if ( $row_sequence[$i - 1] == $col_sequence[ $j - 1] ) {
        $seq_table[$i][$j] = $seq_table[$i - 1][$j - 1] + 1;
        $bridges[] = array( $i, $j );
      } else {
        $seq_table[$i][$j] = ( $seq_table[$i - 1][$j] < $seq_table[$i][$j - 1] ) ?
          $seq_table[$i][$j - 1] : $seq_table[$i - 1][$j];
      }
    }
  }

  // start backtracking
  $lcs = '';

  $i = $ii;
  $j = $jj;

  $moved_left = false;
  $moved_up = false;
  while( $seq_table[$i][$j] > 0 ) {
    if ( $seq_table[$i][$j - 1] == $seq_table[$i][$j] ) {
      $moved_left = true;
    } elseif ( $seq_table[$i - 1][$j] == $seq_table[$i][$j] ) {
      $moved_up = true;
    } else {
      $lcs .= $row_sequence[$i - 1];
      $j--;
      $i--;
    }

    if ( $moved_left) {
      $j--;
    }

    if ( $moved_up ) {
      $i--;
    }

    $moved_left = $moved_up = false;
  }

  return strrev( $lcs );

}

/*
 * Testing section
 */

//require('longest_common_subsequence_tests.php');