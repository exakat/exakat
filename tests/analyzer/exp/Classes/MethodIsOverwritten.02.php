<?php

$expected     = array('function OVERWRITTENMethodInAD( ) { /**/ } ',
                      'function overwrittenMethodInAA( ) { /**/ } ',
                      'function overwrittenMethodInABAC( ) { /**/ } ', // line 21
                      'function overwrittenMethodInABAC( ) { /**/ } ', // linle 26
                     );

$expected_not = array('function intactMethodA( ) { /**/ } ',
                      'function intactMethodAA( ) { /**/ } ',
                      'function intactMethodAB( ) { /**/ } ',
                      'function intactMethodAC( ) { /**/ } ',
                      'function intactMethodAD( ) { /**/ } ',
                     );

?>