<?php

$expected     = array('var_dump($_GET)',
                     );

$expected_not = array('print_r($_GET, $d)',
                      'print_r($_GET, 1)',
                      'print_r($_GET, true)',
                     );

?>