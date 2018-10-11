<?php

$expected     = array('public function __destruct( ) { /**/ } ', 
                      'public function a( ) { /**/ } ', 
                      'public function __clone( ) { /**/ } ',
                     );

$expected_not = array('public function notInterfaceMethod( ) { /**/ } ',
                     );

?>