<?php

$expected     = array('function unusedTMethod( ) { /**/ } ',
                      'function unusedCMethod( ) { /**/ } ',
                     );

$expected_not = array('function usedCMethod( ) { /**/ } ',
                      'function usedTMethod( ) { /**/ } ',
                      'function usedIMethod( ) { /**/ } ',
                      'function unusedIMethod( ) { /**/ } ',
                      'function unusedIMethod( ) ;',
                     );

?>