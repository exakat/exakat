<?php

$expected     = array('function recursive($x) { /**/ } ',
                      'function recursive2($x) { /**/ } ',
                      'static function recursive6($x) { /**/ } ',
                      'static function recursive4($x) { /**/ } ',
                      'static function recursive5($x) { /**/ } ',
                      'static function recursive3a($x) { /**/ } ',
                      'function recursive3b($x) { /**/ } ',
                     );

$expected_not = array('function nonRecursive($x) { /**/ } ',
                      'function nonRecursive2($x) { /**/ } ',
                     );

?>