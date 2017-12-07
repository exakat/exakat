<?php

$expected     = array('function ( ) { /**/ } ',
                      'function ( ) use ($x) { /**/ } ',
                     );

$expected_not = array('function C ($b)',
                      'function Ct ($b) { return 5; } ',
                     );

?>