<?php

$expected     = array('DB2_AUTOCOMMIT_OFF',
                      'db2_rollback($conn)',
                      'db2_exec($conn, \'SELECT count(*) FROM animals\')',
                      'db2_exec($conn, \'SELECT count(*) FROM animals\')',
                      'db2_fetch_array($stmt)',
                      'db2_fetch_array($stmt)',
                      'db2_close($conn)',
                      'db2_connect($database, $user, $password)',
                      'db2_autocommit($conn, DB2_AUTOCOMMIT_OFF)',
                      'db2_exec($conn, \'DELETE FROM animals\')',
                      'db2_exec($conn, \'SELECT count(*) FROM animals\')',
                      'db2_fetch_array($stmt)',
                     );

$expected_not = array('DB2_OTHER_CONSTANT',
                      'db2_fetch_something($stmt)',
                     );

?>