<?php

$expected     = array('function x2($x) : integer { /**/ } ',
                      'function y2($x) : boolean { /**/ } ',
                      'function z2($x) : real { /**/ } ',
                      'function z3($x) : double { /**/ } ',
                     );

$expected_not = array('function x(int $x) { /**/ } ',
                      'function y(bool $x) { /**/ } ',
                      'function z(float $x) { /**/ } ',
                     );

?>