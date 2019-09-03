<?php

$expected     = array('public function bar( ) { /**/ } ',
                     );

$expected_not = array('public function offsetUnset(mixed $offset) : void { /**/ } ',
                      'public function offsetSet(mixed $offset, mixed $value) : void { /**/ } ',
                      'public function offsetGet(mixed $offset) : mixed { /**/ } ',
                      'public function offsetExists(mixed $offset) : bool { /**/ } ',
                      'public function foo( ) { /**/ } ',
                     );

?>