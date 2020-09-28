<?php

$expected     = array('function foo2( ) : A|b { /**/ } ',
                      'function foo3( ) : A|b|c { /**/ } ',
                      'function foo4( ) : A|b|c|\\d { /**/ } ',
                     );

$expected_not = array('function foo0( ) { /**/ } ',
                      'function foo1( ) : A { /**/ } ',
                     );

?>