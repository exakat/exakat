<?php

$expected     = array('function foo9(X &$i) { /**/ } ',
                      'function foo7(numeric &$i) { /**/ } ',
                      'function foo8(mixed &$i) { /**/ } ',
                      'function foo10(A\\B &$i) { /**/ } ',
                     );

$expected_not = array('function foo1(int &$i) { /**/ } ',
                      'function foo2(float &$i) { /**/ } ',
                      'function foo3(bool &$i) { /**/ } ',
                      'function foo4(string &$i) { /**/ } ',
                      'function foo5(callable &$i) { /**/ } ',
                      'function foo6(array &$i) { /**/ } ',
                      'function sfoo1(int $i) { /**/ } ',
                      'function sfoo2(float $i) { /**/ } ',
                      'function sfoo3(bool $i) { /**/ } ',
                      'function sfoo4(string $i) { /**/ } ',
                      'function sfoo5(callable $i) { /**/ } ',
                      'function sfoo6(array $i) { /**/ } ',
                      'function sfoo7(numeric $i) { /**/ } ',
                      'function sfoo8(mixed $i) { /**/ } ',
                      'function sfoo9(X $i) { /**/ } ',
                      'function sfoo10(A\\B $i) { /**/ } ',
                     );

?>