<?php

$expected     = array('function aRelay2(stdclass $a) { /**/ } ',
                      'function aRelay(stdclass $a) { /**/ } ',
                      );

$expected_not = array('function notARelay(stdclass $a) { /**/ } ',
                      'function notARelay2(stdclass $a) { /**/ } ',
                      );

?>