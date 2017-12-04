<?php

$expected     = array('function NotOverwrittenMethod( ) { /**/ } ',
                      'function overwrittenMethodInAA2( ) { /**/ } ',
                      'function LocalMethodInAA( ) { /**/ } ',
                     );

$expected_not = array('function overwrittenMethodInAA( ) { /**/ } ',
                      'function LocalMethodInAA( ) { /**/ } ',
                     );

?>