<?php

$expected     = array('function KillApp( ) { /**/ } ',
                      'function willKillApp( ) { /**/ } ',
                     );

$expected_not = array('function willNotKillApp( ) { /**/ } ',
                      'function willKillApp2ndround( ) { /**/ } ',
                     );

?>