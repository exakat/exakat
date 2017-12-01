<?php

$expected     = array('public function usedStaticallyInStringMethod( ) { /**/ } ',
                      'public function usedStaticallyInArrayMethod( ) { /**/ } ',
                      'public function usedWithThisMethod( ) { /**/ } ',
                     );

$expected_not = array('public function unusedMethod( ) { /**/ } ',
                     );

?>