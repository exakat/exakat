<?php

$expected     = array('function usedFunction( ) { /**/ } ',
                     );

$expected_not = array('function unusedFunction( ) { /**/ } ',
                      'function unusedTraitMethod( ) { /**/ } ',
                      'function usedTraitMethod( ) { /**/ } ',
                      'function unusedInterfaceMethod( ) { /**/ } ',
                      'function usedInterfaceMethod( ) { /**/ } ',
                     );

?>