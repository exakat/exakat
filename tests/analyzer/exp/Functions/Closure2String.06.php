<?php

$expected     = array('fn ( ) => $o->b( )',
                      'fn ( ) => $c::d($a)',
                      'fn ($f) => strtoupper($f)',
                     );

$expected_not = array('function ($n) { /**/ } ',
                      'function foo($n) { /**/ } ',
                     );

?>