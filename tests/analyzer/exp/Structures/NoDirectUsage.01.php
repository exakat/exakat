<?php

$expected     = array('parse_ini_file(\'./someIni.ini\')',
                      'foreach(glob( ) as $x) { /**/ } ',
                      '1 + sqrt($w)',
                     );

$expected_not = array('parse_ini_file(\'./someIni.ini2\')',
                     );

?>