<?php

$expected     = array('\'SELECT name FROM \' . $table_users . \' WHERE id = 1\'',
                     );

$expected_not = array('$a . \' name FROM \' . $table_users . \' WHERE id = 2\'',
                      '"$a " . \' name FROM \' . $table_users . \' WHERE id = 3\'',
                     );

?>