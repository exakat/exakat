<?php

$expected     = array('function notOverwrittenMethod( ) { /**/ } ',
                      'function overwrittenMethod1( ) { /**/ } ',
                      'function usedAbove( ) { /**/ } ',  
                     );

$expected_not = array('function notOverwrittenMethod( ) { /**/ } ',
                      'function usedBelow( ) { /**/ } ',
                      'function overwrittenMethod1( ) { /**/ } ', // allowed to be found ONCE
                     );

?>