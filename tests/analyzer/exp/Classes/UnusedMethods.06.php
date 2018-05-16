<?php

$expected     = array('function unusedCMethod( ) { /**/ } ', 
                      'function abstractCMethod( ) { /**/ } ', 
                      'function unusedCMethod( ) { /**/ } '
                     );

$expected_not = array('function usedCMethod( ) { /**/ } ',
                      'abstract function abstractCMethod( ) { /**/ } ', 
                     );

?>