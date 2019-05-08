<?php

$expected     = array('$_POST',
                      '$_COOKIE[1]',
                      '$_GET[2][3]',
                      '$_REQUEST[2][3][4]',
                     );

$expected_not = array('$_files[\'name\'];',
                      '$_POST',
                      '$_GET',
                      '$_COOKIE',
                     );

?>