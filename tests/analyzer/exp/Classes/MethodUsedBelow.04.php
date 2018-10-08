<?php

$expected     = array('private static function usedprivateByBelowF( ) { /**/ } ',
                      'private static function usedprivateByBelowE( ) { /**/ } ',
                      'private static function usedprivateByBelowC( ) { /**/ } ',
                     );

$expected_not = array('private static function usedprivateByAbove( ) { /**/ } ',
                      'private static function unusedprivate( ) { /**/ } ',
                      'private static function unusedprivateByBelowD( ) { /**/ } ',
                     );

?>