<?php

$expected     = array('public function usedMethod( ) { /**/ } ',
                      'public function __construct($b) { /**/ } ',
                      'public function __construct($c) { /**/ } ',
                     );

$expected_not = array('public function __construct() { /**/ } ',
                     );

?>