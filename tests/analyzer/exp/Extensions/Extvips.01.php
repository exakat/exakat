<?php

$expected     = array('vips_image_new_from_file($argv[1])',
                      'vips_image_write_to_file($x, $argv[2])',
                     );

$expected_not = array('dl(\'vips.\' . PHP_SHLIB_SUFFIX)',
                     );

?>