<?php

$expected     = array('function NotOverwrittenMethod( ) { /**/ } ',
                      'function overwrittenMethodInAA( ) { /**/ } ',
                      'function overwrittenMethodInAA2( ) { /**/ } ',
                      'function LocalMethodInAA( ) { /**/ } ',
                     );

$expected_not = array('function LocalMethodInAA( ) { /**/ } ',
                     );

?>