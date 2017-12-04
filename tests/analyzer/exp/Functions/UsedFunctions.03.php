<?php

$expected     = array('function cmpUsed($a, $b) { /**/ } ',
                      'function cmpUsedFullnspath($a, $b) { /**/ } ',
                      'function b( ) { /**/ } ',
                     );

$expected_not = array('function cmpNotUsed($a, $b) { /**/ } ',
                     );

?>