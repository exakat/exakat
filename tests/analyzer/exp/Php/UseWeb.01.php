<?php

$expected     = array('$_SERVER',
                      '$_POST',
                      '$_COOKIE',
                      '$_REQUEST',
                      '$_GET',
                     );

$expected_not = array('$_ENV',
                      '$argv',
                      '$argc',
                     );

?>