<?php

$expected     = array('protected function parentNotDerived( ) { /**/ } ',
                      'public function grandParentNotDerived( ) { /**/ } ',
                      'public function noParentMethod( ) { /**/ } ',
                      'protected function parentIsConcrete( ) { /**/ } ',
                      'public function parentIsAbstract( ) { /**/ } ',
                      'public function grandParentExists( ) { /**/ } ',
                      'public function parentIsPrivate( ) { /**/ } ',
                      'private function parentIsPrivate( ) { /**/ } ',
                     );

$expected_not = array('public function grandParentExists( ) { /**/ } ',
                      'public function parentIsConcrete( ) { /**/ } ',
                     );

?>