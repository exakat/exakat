<?php

$expected     = array('public static function __isset($a) { /**/ } ',
                      'protected static function __isset($a) { /**/ } ',
                      'protected function __isset($a) { /**/ } ',
                      'private function __isset($a) { /**/ } ',
                     );

$expected_not = array('public function __isset($a) { /**/ } ',
                      'function __isset($a) { /**/ } ',
                     );

?>