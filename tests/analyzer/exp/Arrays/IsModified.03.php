<?php

$expected     = array('$_POST[2]',
                      '$_POST[3][4]',
                      '$_COOKIE[4]',
                      '$_COOKIE[4][5]',
                     );

$expected_not = array('$_GET[3][4]',
                      '$_GET[2]',
                      '$_REQUEST[5][6]',
                      '$_REQUEST[3]',
                      '$c',
                     );

?>