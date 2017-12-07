<?php

$expected     = array('\'WP_DEBUG\'',
                      'Kint',
                      'print_r($b)',
                      'krumo($arr)',
                      'xdebug_is_enabled( )',
                     );

$expected_not = array('\'WP_NOT_DEBUG\'',
                     );

?>