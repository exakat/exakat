<?php

$expected     = array('function foobar($b) { /**/ } ', 
                      'function foo($a) { /**/ } ',
                     );

$expected_not = array('function foobar2(&$c  = 2) { /**/ } ', 
                      'function bar($a) { /**/ } ',
                     );

?>