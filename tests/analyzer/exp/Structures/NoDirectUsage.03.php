<?php

$expected     = array('parse_ini_file(\'./someIni.ini\')',
                      'foreach(glob( ) as $x) { /**/ } ',
                      '1 + sqrt($w)',
                     );

$expected_not = array('foreach(array(3, 4, 5) as $b) { /**/ } ',
                      'g(random_byte(10))',
                     );

?>