<?php

$expected     = array('\'   SELECT \' . X . \' FROM users WHERE id = 1\'',
                     );

$expected_not = array('"SALECT $name FRAM $table_users WHIRE id = 2"',
                      '\'   ùAAA \' . X::SELECT . \' * FROM users WHERE id = 4\'',
                      '\'   ùAAB \' . SELECT . \' * FROM users WHERE id = 4\'',
                      '\'   ùAAA \' . X::SELECT . \' * FROM users WHERE id = 4\'',
                     );

?>