<?php

$expected     = array('SplFileObject');

$expected_not = array('strtolower($y)',
                      "fopen('file', 'r')",
                      "SplFileInfo('filename.php')");

?>