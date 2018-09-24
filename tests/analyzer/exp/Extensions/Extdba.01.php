<?php

$expected     = array('dba_open("/tmp/test.db", "n", "db2")',
                      'dba_replace("key", "This is an example!", $id)',
                      'dba_exists("key", $id)',
                      'dba_fetch("key", $id)',
                      'dba_delete("key", $id)',
                      'dba_close($id)',
                     );

$expected_not = array('dba_remove("key", $id)',
                     );

?>