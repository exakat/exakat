<?php

$expected     = array('private function usedprivateByBelowF( ) { /**/ } ',
                      'private function usedprivateByBelowE( ) { /**/ } ',
                      'private function usedprivateByBelowC( ) { /**/ } ',
                     );

$expected_not = array('private function usedprivateByAbove( ) { /**/ } ',
                      'private function unusedprivate( ) { /**/ } ',
                      'private function unusedprivateByBelowD( ) { /**/ } ',
                     );

?>