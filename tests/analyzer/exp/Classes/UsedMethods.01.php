<?php

$expected     = array('function definedTwiceMethod( ) { /**/ } ', 
                      'function definedTwiceMethod( ) { /**/ } ',
                      'function usedMethod( ) { /**/ } ',
                      'function usedMethodNoCase( ) { /**/ } ',
                      'function usedMethodStatically( ) { /**/ } ',
                      );

$expected_not = array('function unusedMethod( ) { /**/ } ', 
                     );

?>