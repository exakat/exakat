<?php

$expected     = array('\'SELECT name FROM \'', 
                      '\'SELECT name FROM \' . $table_users . \' WHERE id = 2\'', 
                      '\'   SELECT name FROM users WHERE id = 1\'', 
                      '\'SELECT name FROM \' . $table_users . \' WHERE id = 2\'');

$expected_not = array("'SALECT name FROM users WHERE id = 1'");

?>