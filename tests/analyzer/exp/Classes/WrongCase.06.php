<?php

$expected     = array('function a_ko(X $a) { /**/ } ',
                      'function a_ko2(\X $a) { /**/ } ');

$expected_not = array('function a_ko(x $a) { /**/ } ',
                      'function a_ko2(\x $a) { /**/ } ');

?>