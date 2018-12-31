<?php

$expected     = array('\'SELECT name FROM \' . $table_users . \' WHERE id = 2\'',
                      '\'   SELECT name FROM users WHERE id = 1\'',
                      '<<<SQL
SELECT name FROM $table_users WHERE id = 3
SQL',
                      '<<<\'SQL\'
SELECT name FROM $table_users WHERE id = 4
SQL',
                      '<<<\'SQL\'
SELECT name FROM $table_users WHERE id = 5
SQL',
                     );

$expected_not = array('\'SELECT name FROM \'',
                      '\'SALECT name FROM users WHERE id = 1\'',
                     );

?>