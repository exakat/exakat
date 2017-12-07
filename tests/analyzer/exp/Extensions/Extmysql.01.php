<?php

$expected     = array('mysql_connect(\'localhost\', \'mysql_user\', \'mysql_password\')',
                      'mysql_select_db(\'mydb\')',
                      'mysql_query(\'DELETE FROM mytable WHERE id < 10\')',
                      'mysql_query(\'DELETE FROM mytable WHERE 0\')',
                      'mysql_error( )',
                      'mysql_affected_rows( )',
                      'mysql_affected_rows( )',
                     );

$expected_not = array(
                     );

?>