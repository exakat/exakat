<?php

$expected     = array('strtotime("next month", mktime(0, 0, 0, 10, 31, 2017))',
strtotime('+1 month', mktime(0, 0, 0, 10, 31, 2017)), ./test.php, 4, None, None, None
strtotime('+2 month', mktime(0, 0, 0, 10, 31, 2017)), ./test.php, 5, None, None, None
strtotime('-1 month', mktime(0, 0, 0, 10, 31, 2017)), ./test.php, 6, None, None, None
strtotime("+$x month", mktime(0, 0, 0, 10, 31, 2017)), ./test.php, 7, None, None, None',
                      '',
                     );

$expected_not = array('',
                      '',
                     );

?>