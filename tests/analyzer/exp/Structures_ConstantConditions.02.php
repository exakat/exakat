<?php

$expected     = array('while (true) { /**/ } ', 
                      'while (3 << 5) { /**/ } ', 
                      'while ($x) { /**/ } ');

$expected_not = array('while ($z++) { /**/ } ');

?>