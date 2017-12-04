<?php

$expected     = array('public function offsetExists($offsetxx) { /**/ } ',
                      'public function offsetUnset($offsetxx) { /**/ } ',
                      'public function offsetGet($offsetxx) { /**/ } ',
                      'public function offsetSet($offsetxx, $value) { /**/ } ',
                      'public function offsetExists($offset) { /**/ } ',
                      'public function offsetSet($offset, $value) { /**/ } ',
                      'public function offsetUnset($offset) { /**/ } ',
                      'public function offsetGet($offset) { /**/ } ',
                      'public function interfaceIMethod($ix) { /**/ } ',
                      'public function interfaceIMethod($ixx) { /**/ } ',
                     );

$expected_not = array('public function unusedMethody( ) { /**/ } ',
                      'public function unusedMethod( ) { /**/ } ',
                      'public function unusedMethodxx( ) { /**/ } ',
                     );

?>