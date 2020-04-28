<?php

$expected     = array('private function usedThis( ) { /**/ } ',
                      'private function usedSelf( ) { /**/ } ',
                     );

$expected_not = array('private function unused( ) { /**/ } ',
                      'private function usedClass( ) { /**/ } ',
                     );

?>