<?php

$expected     = array('public function uma2($a) { /**/ } ', 
                      'function nma2($a) { /**/ } ', 
                      'protected function oma2($a) { /**/ } '
                     );

$expected_not = array('private function ima2($a) { /**/ } ', 
                      'private function ima1($a) { /**/ } ', 
                      'public function uma1($a) { /**/ } ', 
                      'function nma1($a) { /**/ } ', 
                      'protected function oma1($a) { /**/ } '
                     );

?>