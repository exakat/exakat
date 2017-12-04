<?php

$expected     = array('$_ENV[\'UNUSUAL\']',
                      '$_env[\'TAMP\']',
                     );

$expected_not = array('$_ENV[\'PATH\']',
                      '$_ENV["TEMP"]',
                      '$_env[\'TAMP\']',
                      '$_POST["TEMP"]',
                     );

?>