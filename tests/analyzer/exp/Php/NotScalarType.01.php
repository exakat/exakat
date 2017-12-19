<?php

$expected     = array('function x2(integer $x) { /**/ } ',
                      'function y2(boolean $x) { /**/ } ',
                      'function z2(real $x) { /**/ } ',
                      'function z3(double $x) { /**/ } ',
                     );

$expected_not = array('function x(int $x) { /**/ } ',
                      'function y(bool $x) { /**/ } ',
                      'function z(float $x) { /**/ } ',
                     );

?>