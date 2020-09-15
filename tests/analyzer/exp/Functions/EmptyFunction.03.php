<?php

$expected     = array('public function methodHeritedFromUnknownClass( ) { /**/ } ',
                      'public function methodHeritedFromNotComposerClass( ) { /**/ } ',
                      'public function methodHeritedFromComposer( ) { /**/ } ',
                      'public function methodSubSubHeritedFromComposer( ) { /**/ } ',
                      'public function methodSubHeritedFromComposer( ) { /**/ } ',
                     );

$expected_not = array('public function methodHeritedFromComposer( ) { /**/ } ',
                     );

?>