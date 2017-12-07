<?php

$expected     = array('ibase_fetch_object($sth)',
                      'ibase_free_result($sth)',
                      'ibase_connect($host, $username, $password)',
                      'ibase_query($dbh, $stmt)',
                      'ibase_close($dbh)',
                     );

$expected_not = array(
                     );

?>