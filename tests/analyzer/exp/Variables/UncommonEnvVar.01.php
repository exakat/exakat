<?php

$expected     = array('$_ENV[\'UNUSUAL\']',
                     );

$expected_not = array('$_ENV[\'PATH\']',
                      '$_env[\'TAMP\']',
                      '$_ENV["TEMP"]',
                      '$_env[\'TAMP\']',
                      '$_POST["TEMP"]',
                     );

?>