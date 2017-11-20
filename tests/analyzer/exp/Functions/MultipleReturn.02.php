<?php

$expected     = array('function ($a2, Array $b2) { /**/ } ',
                      'function ab( ) { /**/ } ',
                     );

$expected_not = array('function a( ) { /**/ }',
                      'function ( $a, Array $b ) { return in_array($a, $b); };',
                     );

?>