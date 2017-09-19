<?php

$expected     = array('protected function usedProtectedByBelowF( ) { /**/ } ',
                      'protected function usedProtectedByBelowE( ) { /**/ } ',
                      'protected function usedProtectedByBelowC( ) { /**/ } ',
                      );

$expected_not = array('protected function usedProtectedByAbove( ) { /**/ } ',
                      'protected function unusedProtected( ) { /**/ } ',
                      'protected function unusedProtectedByBelowD( ) { /**/ } ',
                     );

?>