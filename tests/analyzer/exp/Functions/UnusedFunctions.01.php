<?php

$expected     = array('function unusedFunction( ) { /**/ } ',
                     );

$expected_not = array('function usedFunction( ) { /**/ } ',
                      'function unusedMethod( ) { /**/ } ',
                      'function usedMethod( ) { /**/ } ',
                     );

?>