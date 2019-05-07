<?php

$expected     = array('php_version( )',
                      'PHP_SAPI_NAME( )',
                      'fopen(\'php://stdin\', \'r\')',
                      'fopen("php://stdout", \'w\')',
                      'fopen(\'php://stderr\', \'w\')',
                     );

$expected_not = array('fopen(\'file://etc/test.php\', \'r\');',
                      '\\a\\b\\PHP_sapi_name( )',
                     );

?>