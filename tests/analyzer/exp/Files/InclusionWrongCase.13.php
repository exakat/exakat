<?php

$expected     = array('include \'include.PHP\'',
                      'include A',
                     );

$expected_not = array('include \'include.php\'',
                      'include _FILE_OPTIONS',
                      'include PHP_VERSION',
                      'include B',
                     );

?>