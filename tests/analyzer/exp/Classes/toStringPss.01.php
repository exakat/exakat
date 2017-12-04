<?php

$expected     = array('public static function __toString( ) { /**/ } ',
                      'protected static function __toString( ) { /**/ } ',
                      'protected function __toString( ) { /**/ } ',
                      'private function __toString( ) { /**/ } ',
                     );

$expected_not = array('public function __toString( ) { /**/ } ',
                      'function __toString( ) { /**/ } ',
                     );

?>