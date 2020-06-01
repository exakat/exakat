<?php

$expected     = array('function notOverwrittenMethod( ) { /**/ } ',
                      'function overwrittenMethod1( ) { /**/ } ',
                     );

$expected_not = array('function notOverwrittenMethod( ) { /**/ } ',
                      'function usedBelow( ) { /**/ } ',
                      'function usedAbove( ) { /**/ } ',  // might need to find this..
                      'function overwrittenMethod1( ) { /**/ } ', // allowed to be found ONCE
                     );

?>