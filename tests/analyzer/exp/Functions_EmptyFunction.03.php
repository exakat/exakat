<?php

$expected     = array('public function methodHeritedFromUnknownClass( ) { /**/ } ');

$expected_not = array('public function methodSubSubHeritedFromComposer ( ) { /**/ } ',
                      'public function methodSubHeritedFromComposer ( ) { /**/ } ',
                      'public function methodHeritedFromComposer ( ) { /**/ } ',
                      );

?>