<?php

$expected     = array('public function onlyInAA( ) { /**/ } ',
                      'public function onlyInBA( ) { /**/ } ',
                      'public function onlyInCA( ) { /**/ } ',
                      'public function onlyInDA( ) { /**/ } ',
                      'public function grandParentExists( ) { /**/ } ',
                      'public function grandParentNotDerived( ) { /**/ } ',
                      'protected function parentNotDerived( ) { /**/ } ',
                      'public function parentIsConcrete( ) { /**/ } ',
                      'private function parentIsConcrete( ) { /**/ } ',
                     );

$expected_not = array('public function parentIsConcrete( ) { /**/ } ',
                      'public function grandParentExists( ) { /**/ } ',
                     );

?>