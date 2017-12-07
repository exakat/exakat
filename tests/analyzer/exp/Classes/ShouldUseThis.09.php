<?php

$expected     = array('function methodDontUseThis( ) { /**/ } ',
                     );

$expected_not = array('function c($d) { /**/ } ',
                      'function g($f) { /**/ } ',
                      'function   ($ab) { /**/ } ',
                      'function methodUseThis( ) { /**/ } ',
                     );

?>