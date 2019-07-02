<?php

$expected     = array('function OVERWRITTENMethodInAD( ) { /**/ } ',
                      'function overwrittenMethodInAA( ) { /**/ } ',
                      'function overwrittenMethodInABAC( ) { /**/ } ', // line 18
                      'function overwrittenMethodInABAC( ) { /**/ } ', // line 23
                     );

$expected_not = array('function intactMethodA( ) { /**/ } ',
                      'function intactMethodAA( ) { /**/ } ',
                      'function intactMethodAB( ) { /**/ } ',
                      'function intactMethodAC( ) { /**/ } ',
                      'function intactMethodAD( ) { /**/ } ',
                     );

?>