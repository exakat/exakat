<?php

$expected     = array('function &fooo1( ) { /**/ } ',
                      'function &fooo3( ) { /**/ } ',
                      'function &foo1($a) { /**/ } ',
                      'function &foo2($a) { /**/ } ',
                      'function &foo3($a) { /**/ } ',
                     );

$expected_not = array('function &fooo2( ) { /**/ } ',
                      'function &fooo4( ) { /**/ } ',
                     );

?>