<?php

$expected     = array('private function privatema( ) { /**/ } ',
                      'private function privatem( ) { /**/ } ',
                      'private static function privatepmstatic( ) { /**/ } ',
                      'private static function privatemsself( ) { /**/ } ',
                     );

$expected_not = array('private function privateUnused( ) { /**/ } ',
                      'private function publicp( ) { /**/ } ',
                     );

?>