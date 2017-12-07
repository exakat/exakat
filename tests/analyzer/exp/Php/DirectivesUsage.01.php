<?php

$expected     = array('ini_restore(\'user_dir\')',
                      '\\ini_get(\'asp_tags\')',
                      'ini_alter(\'open_basedir\', 3)',
                      'ini_set(\'always_populate_raw_post_data\', 1)',
                     );

$expected_not = array('ini_restore(\'user_ini_dir\')',
                      '\\ini_get(\'jsp_tags\')',
                     );

?>