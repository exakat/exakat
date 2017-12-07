<?php

$expected     = array('mysql_connect(1, 2, 3, 4) or die( )',
                      'mysqli_connect(1, 2, 3, 4) or exit( )',
                      'ora_bind(1, 2, 3, 4) || die( )',
                     );

$expected_not = array('die( ) || pg_connect(1, 2, 3, 4)',
                     );

?>