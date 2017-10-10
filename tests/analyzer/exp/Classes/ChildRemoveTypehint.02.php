<?php

$expected     = array('function dropTypehint(A $a) { /**/ } ',
                      );

$expected_not = array('function keepTypehint(A $a) { /**/ } ',
                      'function addTypehint(A $a) { /**/ } ',
                      'function notOverwritten(A $a) { /**/ } ',
                      'function notOverwritten2( ) { /**/ } ',
                      'function notInParent( ) { /**/ } ',
                      'function notInParent2( ) { /**/ } ',
                      );

?>