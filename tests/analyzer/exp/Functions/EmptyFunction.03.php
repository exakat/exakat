<?php

$expected     = array('public function methodHeritedFromUnknownClass( ) { /**/ } ',
                      'public function methodHeritedFromNotComposerClass( ) { /**/ } ',
                      'public function methodHeritedFromComposer( ) { /**/ } ',
                     );

$expected_not = array('public function methodSubSubHeritedFromComposer( ) { /**/ } ',
                      'public function methodSubHeritedFromComposer( ) { /**/ } ',
                      'public function methodHeritedFromComposer( ) { /**/ } ',
                     );

?>