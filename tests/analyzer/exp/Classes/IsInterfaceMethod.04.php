<?php

$expected     = array('public function offsetUnset($offset) { /**/ } ',
                      'public function offsetExists($offset) { /**/ } ',
                      'public function offsetGet($offset) { /**/ } ',
                      'public function offsetSet($offset, $value) { /**/ } ',
                      'function mj($mi1, $mi2, $mi3) { /**/ } ',
                      'function mi($mi1, $mi2, $mi3) { /**/ } ',
                     );

$expected_not = array('function mb($mi1, $mi2, $mi3) { /**/ } ',
                      'function mh($mi1, $mi2, $mi3) { /**/ } ',
                     );

?>