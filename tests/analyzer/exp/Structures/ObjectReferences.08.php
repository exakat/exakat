<?php

$expected     = array('function foo2(&$a) { /**/ } ',
                     );

$expected_not = array('function foo(&$a) { /**/ } ',
                      'function foo3(&$a) { /**/ } ',
                      'function foo4($a) { /**/ } ',
                     );

?>