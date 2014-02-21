<?php

$filename = tempnam('/tmp', 'zlibtest') . '.gz';

// open file for writing with maximum compression
$zp = gzopen($filename, "w9");

// write string to file
gzwrite($zp, $s);

// close file
gzclose($zp);

?>