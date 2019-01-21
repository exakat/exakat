<?php

$expected     = array('private function y_pri( ) { /**/ } ',
                      'protected function y_pro( ) { /**/ } ',
                     );

$expected_not = array('private function w_pri( ) { /**/ } ',
                      'protected function w_pro( ) { /**/ } ',
                      'private function w_pri( ) { /**/ } ',
                      
                      'private function z_pri( ) { /**/ } ',
                      'protected function z_pro( ) { /**/ } ',
                      'private function z_pri( ) { /**/ } ',
                      
                      'private function y_pri( ) { /**/ } ',
                     );

?>