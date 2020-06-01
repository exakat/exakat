<?php

$expected     = array('function unusedCMethod( ) { /**/ } ',
                      'function abstractCMethod( ) { /**/ } ',
                     );

$expected_not = array('function usedCMethod( ) { /**/ } ',
                      'abstract function abstractCMethod( ) { /**/ } ',
                      'function unusedCMethod( ) { /**/ } ', // Only one has to be found. The other is overwritten, so OK.
                     );

?>