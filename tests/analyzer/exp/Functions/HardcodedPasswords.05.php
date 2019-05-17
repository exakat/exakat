<?php

$expected     = array('mysql_connect(\'localhost\', \'root\', \'abc\')',
                      'mysql_connect(\'localhost\', \'root\', MYSQL_PASS)',
                      'mysql_connect(\'localhost\', \'root\', A::MYSQL_PASS)',
                      'mysql_connect(\'localhost\', \'root\', $not_a_password)',
                     );

$expected_not = array('mysql_connect(\'localhost\', \'root\', null)',
                      'mysql_connect(\'localhost\', \'root\', \'abc\')',
                     );

?>