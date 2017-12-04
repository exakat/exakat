<?php

$expected     = array('private function methodIPrivate( ) { /**/ } ',
                      'protected function methodIProtected( ) { /**/ } ',
                     );

$expected_not = array('private function methodIPublic( ) { /**/ } ',
                      'protected function methodINone( ) { /**/ } ',
                     );

?>