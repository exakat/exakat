<?php

$expected     = array('public function offsetExists($offsetExistsxx) { /**/ } ',
                      'public function offsetUnset($offsetUnsetxx) { /**/ } ',
                      'public function offsetGet($offsetGetxx) { /**/ } ',
                      'public function offsetSet($offsetSetxx, $value) { /**/ } ',
                      'public function interfaceIMethod($interfaceIMethodxx) { /**/ } ',
                      'public function offsetExists($offset) { /**/ } ',
                      'public function offsetSet($offset, $value) { /**/ } ',
                      'public function offsetUnset($offset) { /**/ } ',
                      'public function offsetGet($offset) { /**/ } ',
                      'public function interfaceIMethod($ix) { /**/ } ',
                      'public function interfaceIMethod($interfaceIMethodxxx) { /**/ } ',
                      'public function offsetGet($offsetGetxxx) { /**/ } ',
                      'public function offsetExists($offsetExistsxxx) { /**/ } ',
                      'public function offsetSet($offsetSetxxx, $value) { /**/ } ',
                      'public function offsetUnset($offsetUnsetxxx) { /**/ } ',
                      'public function interfaceIMethod($interfaceIMethodxxxx) { /**/ } ',
                      'public function offsetExists($offsetExistsxxxx) { /**/ } ',
                      'public function offsetSet($offsetSetxxxx, $value) { /**/ } ',
                      'public function offsetUnset($offsetUnsetxxxx) { /**/ } ',
                      'public function offsetGet($offsetGetxxxx) { /**/ } ',
                     );

$expected_not = array('public function unusedMethody( ) { /**/ } ',
                      'public function unusedMethod( ) { /**/ } ',
                      'public function unusedMethodxx( ) { /**/ } ',
                     );

?>