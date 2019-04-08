<?php

$expected     = array('\'   select \' . x . \' from users where id = 1\'',
                     );

$expected_not = array('"salect $name fram $table_users whire id = 2"',
                      '\'   ùaaa \' . x::select . \' * from users where id = 4\'',
                      '\'   ùaab \' . select . \' * from users where id = 4\'',
                      '\'   ùaaa \' . x::select . \' * from users where id = 4\'',
                     );

?>