<?php

$expected     = array('php_version( )',
                      'PHP_SAPI( )',
                      'fopen(\'php://stdin\', \'r\')',
                      'fopen("php://stdout", \'w\')',
                      'fopen(\'php://stderr\', \'w\')',
                     );

$expected_not = array('fopen(\'file://etc/test.php\', \'r\');',
                     );

?>