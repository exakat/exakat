<?php

$expected     = array('public function interfaceimethod( ) { /**/ } ',
                      'PUBLIC FUNCTION INTERFACEIMETHOD( ) { /**/ } ',
                      'public function offsetset($offset, $value) { /**/ } ',
                      'public function offsetget($offset) { /**/ } ',
                      'public function offsetunset($offset) { /**/ } ',
                      'public function offsetexists($offset) { /**/ } ',
                      'PUBLIC FUNCTION OFFSETSET($OFFSETXX, $VALUE) { /**/ } ',
                      'PUBLIC FUNCTION OFFSETGET($OFFSETXX) { /**/ } ',
                      'PUBLIC FUNCTION OFFSETEXISTS($OFFSETXX) { /**/ } ',
                      'PUBLIC FUNCTION OFFSETUNSET($OFFSETXX) { /**/ } ',
                     );

$expected_not = array('public function unusedMethody( ) { /**/ } ',
                      'public function UNUSEDMETHOD( ) { /**/ } ',
                      'public function UNUSEDMETHODXX( ) { /**/ } ',
                     );

?>