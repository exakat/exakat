<?php

$expected     = array('static function aSelf(x $z) { /**/ } ',
                     );

$expected_not = array('static function aStatic(y $z) { /**/ } ',
                      'static function aX(x $z) { /**/ } ',
                      'static function aNsname($z) { /**/ } ',
                     );

?>