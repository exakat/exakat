<?php

$expected     = array('protected function parentNotDerived( ) { /**/ } ',
                      'public function grandParentNotDerived( ) { /**/ } ',
                      'public function noParentMethod( ) { /**/ } ',
                      'private function parentIsConcrete( ) { /**/ } ',
                      'public function parentIsConcrete( ) { /**/ } ',
                      'public function parentIsAbstract( ) { /**/ } ',
                      'public function grandParentExists( ) { /**/ } ',
                     );

$expected_not = array('public function grandParentExists( ) { /**/ } ',
                     );

?>