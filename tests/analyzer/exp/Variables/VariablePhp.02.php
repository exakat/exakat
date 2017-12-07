<?php

$expected     = array('$argc',
                      '$_POST',
                      '$_GET',
                      '$PHP_SELF',
                      '$GLOBALS',
                      '$argv',
                      '$_REQUEST',
                     );

$expected_not = array('$COOKIE',
                      '$globals',
                     );

?>