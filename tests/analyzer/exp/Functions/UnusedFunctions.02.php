<?php

$expected     = array('function unusedFunction( ) { /**/ } ',
                     );

$expected_not = array('function usedFunction( ) { /**/ } ',
                      'function unusedTraitMethod( ) { /**/ } ',
                      'function usedTraitMethod( ) { /**/ } ',
                      'function unusedInterfaceMethod( ) { /**/ } ',
                      'function usedInterfaceMethod( ) { /**/ } ',
                     );

?>