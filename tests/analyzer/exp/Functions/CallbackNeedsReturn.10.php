<?php

$expected     = array('array_map("cube2", $a)',
                     );

$expected_not = array('array_map("cube", $a)',
                      'register_shutdown_function("cube2", $a)',
                      'register_shutdown_function("cube2", $a)',
                     );

?>