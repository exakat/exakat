<?php

$expected     = array('public function grandParentNotDerived ( ) { /**/ } ',
                      'private function parentIsConcrete ( ) { /**/ } ',
                      'public function noParentMethod ( ) { /**/ } ',
                      'public function grandParentExists ( ) { /**/ } ',
                      'protected function parentNotDerived ( ) { /**/ } ');

$expected_not = array('public function parentIsAbstract ( ) { /**/ } ',
                      'public function parentIsConcrete ( ) { /**/ } ',
                      'public function grandParentExists ( ) { /**/ } ');

?>