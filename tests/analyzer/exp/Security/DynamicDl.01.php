<?php

$expected     = array('dl($library . PHP_SHLIB_SUFFIX)',
                     );

$expected_not = array('dl(\'vips.\' . PHP_SHLIB_SUFFIX)',
                     );

?>