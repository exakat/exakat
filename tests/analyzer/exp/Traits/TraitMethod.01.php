<?php

$expected     = array('abstract function traitMethod2( ) ;',
                      'function traitMethod( ) { /**/ } ',
                     );

$expected_not = array('function classMethod( ) { /**/ } ',
                      'function classMethod2( ) { /**/ } ',
                      'function interfaceMethod( ) { /**/ } ',
                      'function interfaceMethod2( ) { /**/ } ',
                     );

?>