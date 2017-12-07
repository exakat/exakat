<?php

$expected     = array('pg_connect("dbname=publisher")',
                      'pg_connection_busy($dbconn)',
                      'pg_send_query($dbconn, "select * from authors; select count(*) from authors;")',
                      'pg_get_result($dbconn)',
                      'pg_num_rows($res1)',
                      'pg_get_result($dbconn)',
                      'pg_num_rows($res2)',
                     );

$expected_not = array('die("Could not connect")',
                     );

?>