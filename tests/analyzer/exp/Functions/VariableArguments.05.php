<?php

$expected     = array('function d($d, $e) { /**/ } ',
                     );

$expected_not = array('function a($d, $e) { /**/ } ',
                      'function b2($b, Stdclass ...$c2) { /**/ } ',
                      'function a($b, ...$c) { /**/ } ',
                     );

?>