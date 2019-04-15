<?php

$expected     = array('function split( ) { /**/ } ',
                     );

$expected_not = array('function spliti( ) { /**/ } ',
                      'function spliti($a) { /**/ } ',
                      'function spliti($c) { /**/ } ',
                     );

?>