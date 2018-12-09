<?php

$expected     = array('function foo2( ) : parent { /**/ } ',
                      'function __set(parent $x, parent $y) { /**/ } ',
                      'function foo(parent $x, grandparent $y) { /**/ } ',
                      'function __get(parent $x) : parent { /**/ } ',
                     );

$expected_not = array('function foo3(parent $x, grandparent $y) { /**/ } ',
                      'function foo4( ) : parent { /**/ } ',
                      'function __set(parent $x2, parent $y) { /**/ } ',
                      'function __get(parent $x2) : parent { /**/ } ',
                     );

?>