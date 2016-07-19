<?php

$expected     = array('public function methodHeritedFromUnknownClass( ) { /**/ } ',
                      //'public function methodHeritedFromNotComposerClass( ) { /**/ } '
                      );

$expected_not = array(//'public function methodSubSubHeritedFromComposer( ) { /**/ } ',
                      //'public function methodSubHeritedFromComposer( ) { /**/ } ',
                      //'public function methodHeritedFromComposer( ) { /**/ } ',
                      );

?>