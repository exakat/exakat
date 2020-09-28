<?php

$expected     = array('$f',
                      'function moo(array $m) { /**/ } ',
                      'function koo( ) { /**/ } ',
                     );

$expected_not = array('function boo($b) { /**/ } ',
                      'function coo(array $c) : array { /**/ } ',
                     );

?>