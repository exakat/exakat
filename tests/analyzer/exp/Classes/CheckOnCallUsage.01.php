<?php

$expected     = array('static function __callstatic($a1, $b1) { /**/ } ',
                      'static function __callstatic($a5, $b1) { /**/ } ',
                      'static function __callstatic($a6, $b1) { /**/ } ',
                      'function __call($a1, $b1) { /**/ } ',
                     );

$expected_not = array('static function __callstatic($a4, $b1) { /**/ } ',
                      'function __call($a4, $b1) { /**/ } ',
                     );

?>