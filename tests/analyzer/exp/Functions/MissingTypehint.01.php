<?php

$expected     = array('function bar1(string $a3) { /**/ } ',
                      'function (string $a6) { /**/ } ',
                      'function foo_method( ) { /**/ } ',
                      'function foo_method($b) { /**/ } ',
                      'function __construct($c) { /**/ } ',
                      'function foo($a) : void { /**/ } ',
                     );

$expected_not = array('function __get($a7) { /**/ } ',
                      'function bar2(string $a4) : void { /**/ } ',
                     );

?>