<?php

$expected     = array('$argc',
                      '$_POST',
                      '$_GET',
                      '$GLOBALS',
                      '$argv',
                      '$_REQUEST',
                     );

$expected_not = array('$COOKIE',
                      '$PHP_SELF',
                      '$globals',
                     );

?>