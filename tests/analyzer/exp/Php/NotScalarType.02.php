<?php

$expected     = array('function z2(real $x) { /**/ } ',
                      'function y2(boolean $x) { /**/ } ',
                     );

$expected_not = array('function x2(integer $x) { /**/ } ',
                      'function z3(double $x) { /**/ } ',
                      'function x(int $x) { /**/ } ',
                      'function y(bool $x) { /**/ } ',
                      'function z(float $x) { /**/ } ',
                     );

?>