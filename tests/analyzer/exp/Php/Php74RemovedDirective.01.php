<?php

$expected     = array('ini_set(\'allow_url_include\', 1)',
                     );

$expected_not = array('ini_get(\'ALLOW_URL_INCLUDE\', 1)',
                      'ini_met(\'allow_url_include\', 1)',
                     );

?>