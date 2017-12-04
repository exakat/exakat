<?php

$expected     = array('\'SELECT name FROM \' . $table_users . \' WHERE id = 2\'',
                      '\'   SELECT name FROM users WHERE id = 1\'',
                     );

$expected_not = array('\'SELECT name FROM \'',
                      '\'SALECT name FROM users WHERE id = 1\'',
                     );

?>