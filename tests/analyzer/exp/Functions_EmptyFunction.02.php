<?php

$expected     = array('protected function parentNotDerived( ) { /**/ } ', 
                      'public function grandParentNotDerived( ) { /**/ } ', 
                      'public function noParentMethod( ) { /**/ } ', 
                      'private function parentIsConcrete( ) { /**/ } ', 
                      'public function grandParentExists( ) { /**/ } ');

$expected_not = array('public function parentIsAbstract ( ) { /**/ } ',
                      'public function parentIsConcrete ( ) { /**/ } ',
                      'public function grandParentExists ( ) { /**/ } ');

?>