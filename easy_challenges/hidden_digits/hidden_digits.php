<?php
$fh = fopen($argv[1], "r");
$ascii_a = ord('a');
$ascii_j = ord('j');
while (!feof($fh)) {
	$test = fgets($fh);
	if (!empty($test)) {
		$chars = str_split($test);
		$line = '';
		foreach ($chars as $char) {
			// is digit
			if (is_numeric($char)) {
				$line .= $char;
				continue;
			}
			// is a - j
			$ascii = ord($char);
			if ($ascii >= $ascii_a && $ascii <= $ascii_j) {
				$line .= ($ascii - $ascii_a);
				continue; 
			}
		}

		echo (empty($line)) ? "NONE\n" : $line . "\n";
	}
}
fclose($fh);
?>