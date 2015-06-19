<?php

$expected     = array('public function interfaceimethod ( ) { /**/ } ', 
                      'public function INTERFACEIMETHOD ( ) { /**/ } ', 

                      'public function offsetset ($offset, $value) { /**/ } ', 
                      'public function offsetget ($offset) { /**/ } ', 
                      'public function offsetunset ($offset) { /**/ } ', 
                      'public function offsetexists ($offset) { /**/ } ', 

                      'public function OFFSETSET ($OFFSETXX, $VALUE) { /**/ } ', 
                      'public function OFFSETGET ($OFFSETXX) { /**/ } ', 
                      'public function OFFSETEXISTS ($OFFSETXX) { /**/ } ', 
                      'public function OFFSETUNSET ($OFFSETXX) { /**/ } ' );

$expected_not = array('public function unusedMethody ( ) { /**/ } ',
                      'public function UNUSEDMETHOD ( ) { /**/ } ',
                      'public function UNUSEDMETHODXX ( ) { /**/ } ',);

?>