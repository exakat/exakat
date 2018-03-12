<?php

$expected     = array('fopen(\'./\' . SITE, \'r+\')',
                      'fopen("./$site", \'r+\')',
                     );

$expected_not = array('fopen(\'http://www.php.net/\',\'r+\')',
                      'fopen("https://$site",\'r+\')',
                      'fopen(\'http://\'.$site2.\'/\',\'r+\')',
                     );

?>