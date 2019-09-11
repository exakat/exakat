<?php

$expected     = array('private function fooVisibility4($a, $b) { /**/ } ',
                      'private function fooVisibility3($a, $b) { /**/ } ',
                      'protected function fooVisibility1($a, $b) { /**/ } ',
                      'function fooNullableTypehint2(B $a) { /**/ } ',
                      'function fooNullableTypehint3(?C $a) { /**/ } ',
                      'function fooTypehint2(A $a) { /**/ } ',
                      'function fooTypehint3($a) { /**/ } ',
                      'function fooReference2($a, $b) { /**/ } ',
                      'function fooReference3($a, $b) { /**/ } ',
                     );

$expected_not = array('private function fooVisibility1($a, $b) { /**/ } ',
                      'private function fooVisibility2($a, $b) { /**/ } ',
                     );

?>