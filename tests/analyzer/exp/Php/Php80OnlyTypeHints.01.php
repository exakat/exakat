<?php

$expected     = array('function foo( ) : stringable { /**/ } ',
                      'function foo2(stringable $a) { /**/ } ',
                      'function foo3(\\stringable $a) { /**/ } ',
                      'function foo4( ) : null|x|false { /**/ } ',
                      'function foo5(null|x|false $a) { /**/ } ',
                     );

$expected_not = array('function foo6(x|c $a) : d|e { /**/ } ',
                     );

?>