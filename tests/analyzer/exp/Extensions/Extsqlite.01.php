<?php

$expected     = array('sqlite_open(\'mysqlitedb\')',
                      'sqlite_exec($dbhandle, "UPDATE users SET email=\'jDoe@example.com\' WHERE username=\'jDoe\'", $error)',
                      'sqlite_changes($dbhandle)',
                     );

$expected_not = array(
                     );

?>