<?php

$expected     = array('$_ENV[3]',
                     );

$expected_not = array('$_SESSION',
                      '$_COOKIE',
                      '$_SERVER[\'HTTP\']',
                      '$_SERVER',
                     );

?>