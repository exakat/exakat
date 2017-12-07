<?php

$expected     = array('readline("Command: ")',
                      'readline_add_history($line)',
                      'readline_info( )',
                     );

$expected_not = array('print_r(readline_info())',
                     );

?>