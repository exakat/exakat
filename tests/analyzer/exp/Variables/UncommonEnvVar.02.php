<?php

$expected     = array('getenv(\'UNUSUAL\')',
                     );

$expected_not = array('getenv(\'PATH\')',
                      'getenv("TEMP")',
                      'strtolower(\'TAMP\')',
                      'readenv("TEMP")',
                     );

?>