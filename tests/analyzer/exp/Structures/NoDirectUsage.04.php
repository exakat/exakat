<?php

$expected     = array('parse_ini_file(\'./someIni.ini\')',
                      '1 + sqrt($w)',
                     );

$expected_not = array('foreach(glob( ) as $x) { /**/ } ',
                      'glob(\'./someIni.ini\')',
                     );

?>