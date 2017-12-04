<?php

$expected     = array('PROTECTED FUNCTION PARENTNOTDERIVED( ) { /**/ } ',
                      'PUBLIC FUNCTION GRANDPARENTNOTDERIVED( ) { /**/ } ',
                      'public function noParentMethod( ) { /**/ } ',
                      'PRIVATE FUNCTION PARENTISCONCRETE( ) { /**/ } ',
                      'public function parentIsConcrete( ) { /**/ } ',
                      'public function parentIsAbstract( ) { /**/ } ',
                      'PUBLIC FUNCTION GRANDPARENTEXISTS( ) { /**/ } ',
                     );

$expected_not = array('public function grandParentExists( ) { /**/ } ',
                     );

?>