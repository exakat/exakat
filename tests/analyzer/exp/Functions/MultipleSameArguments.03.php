<?php

$expected     = array('static function b($f, $f) { /**/ } ',
                      'function a($a, $a) { /**/ } ',
                      'function __construct($f, $f) { /**/ } ',
                      'function b($e, $e) { /**/ } ',
                      'function __construct($d, $d) { /**/ } ',
                      'function ($b, $b) { /**/ } ',
                      'function ($b) use ($b) { /**/ } ',
                     );

$expected_not = array('',
                      '',
                     );

?>