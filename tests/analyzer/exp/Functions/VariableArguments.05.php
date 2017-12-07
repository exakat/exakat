<?php

$expected     = array('function b2($b, Stdclass ...$c2) { /**/ } ',
                      'function a($b, ...$c) { /**/ } ',
                     );

$expected_not = array('function a($d, $e) { /**/ } ',
                     );

?>