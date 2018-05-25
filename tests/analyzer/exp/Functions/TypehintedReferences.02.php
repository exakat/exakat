<?php

$expected     = array('function fooF(X &$i) { /**/ } ',
                      'function (X &$i) { /**/ } ',
                      'function fooI(X &$i) ;',
                      'function __construct(X &$i) { /**/ } ',
                      'function fooT(X &$i) { /**/ } ',
                      'function fooC(X &$i) { /**/ } ',
                     );

$expected_not = array('function fooI2(X $i) ;',
                     );

?>