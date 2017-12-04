<?php

$expected     = array('fopen(\'./\' . SITE, \'r+\')',
                     );

$expected_not = array('fopen("./$site", \'r+\')',
                      'fopen(\'http://www.php.net/\',\'r+\')',
                      'fopen("https://$site",\'r+\')',
                      'fopen(\'http://\'.$site2.\'/\',\'r+\')',
                     );

?>