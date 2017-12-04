<?php

$expected     = array('mysqli_connect("localhost", "my_user", "my_password", "world")',
                      'mysqli_connect_errno( )',
                      'mysqli_connect_error( )',
                     );

$expected_not = array('printf',
                      'exit( )',
                     );

?>