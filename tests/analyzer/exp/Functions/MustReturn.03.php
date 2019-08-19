<?php

$expected     = array('function ($fooc) : string { /**/ } ',
                      'function foof( ) : int { /**/ } ',
                      'function foo( ) : array { /**/ } ',
                     );

$expected_not = array('function foo1() : void { /**/ } ',
                      'function foo3() : iterable { /**/ } ',
                      'function foo2() : void { /**/ } ',
                     );

?>