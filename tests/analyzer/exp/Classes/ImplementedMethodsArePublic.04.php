<?php

$expected     = array('private function methodIPrivate( ) { /**/ } ',
                      'protected function methodIProtected( ) { /**/ } ',
                      'private function methodKPrivate( ) { /**/ } ',
                      'protected function methodKProtected( ) { /**/ } ',
                      'private function methodJPrivate( ) { /**/ } ',
                      'protected function methodJProtected( ) { /**/ } ',
                     );

$expected_not = array('private function methodIPublic( ) { /**/ } ',
                      'protected function methodINone( ) { /**/ } ',
                      'private function methodIPublic( ) { /**/ } ',
                      'protected function methodJNone( ) { /**/ } ',
                      'private function methodKPublic( ) { /**/ } ',
                      'protected function methodKNone( ) { /**/ } ',
                     );

?>