<?php

$expected     = array('function bothpropertyandmethod( ) { /**/ } ', 
                      'function method( ) { /**/ } ', 
                      'function property( ) { /**/ } ',
                     );

$expected_not = array('function nothingButStatic( ) { /**/ } ', 
                      );

?>