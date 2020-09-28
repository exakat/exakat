<?php

$expected     = array('function ($a = [ ], $b) { /**/ } ',
                      'function ($a = null, $b) { /**/ } ',
                     );

$expected_not = array('function (?Foo $a) { /**/ } ',
                      'function (Foo $a = null) {} ;',
                      'function (Foo $a = null, $b) { /**/ } ',
                     );

?>