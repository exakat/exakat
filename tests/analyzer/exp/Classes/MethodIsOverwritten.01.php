<?php

$expected     = array('function OVERWRITTENMethodInAD( ) { /**/ } ',
                      'function overwrittenMethodInAA( ) { /**/ } ',
                      'function overwrittenMethodInABAC( ) { /**/ } ',
                      'function overwrittenMethodInABAC( ) { /**/ } ',
                     );

$expected_not = array('function intactMethodA( ) { /**/ } ',
                      'function intactMethodAA( ) { /**/ } ',
                      'function intactMethodAB( ) { /**/ } ',
                      'function intactMethodAC( ) { /**/ } ',
                      'function intactMethodAD( ) { /**/ } ',
                     );

?>