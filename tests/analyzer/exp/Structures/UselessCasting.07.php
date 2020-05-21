<?php

$expected     = array('(string) strtolower($x)',
                     );

$expected_not = array('(string) file_get_contents($x)',
                      '(int) ini_restore()',
                      '(int) file_get_contents($x)',
                      '(array) file_get_contents($x)',
                     );

?>