<?php

$expected     = array('protected static function __callstatic($a, $b) { /**/ } ', 
                      'private static function __callstatic($a, $b) { /**/ } ', 
                      'protected function __callstatic($a, $b) { /**/ } ', 
                      'private function __callstatic($a, $b) { /**/ } ',
                      'public function __callstatic($a, $b) { /**/ } ',
                      'function __callstatic($a, $b) { /**/ } ',
                      'public final function __callstatic($a, $b) { /**/ } '
                      );

$expected_not = array('static public function __callstatic($a, $b) { /**/ } ',
                      'static public final function __callstatic($a, $b) { /**/ } ',
                       );

?>