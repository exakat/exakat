<?php

$expected     = array('ini_restore(\'xsl.security_prefs\')',
                      '\\ini_get(\'asp_tags\')',
                      'ini_alter(\'xsl.security_prefs\', 3)',
                      'ini_set(\'always_populate_raw_post_data\', 1)',
                     );

$expected_not = array('\\ini_get(\'jsp_tags\')',
                     );

?>