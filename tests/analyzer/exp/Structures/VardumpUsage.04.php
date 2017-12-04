<?php

$expected     = array('call_user_func(\'var_dump\', $a)',
                      'call_user_func_array("PRINT_R", $a)',
                      'call_user_func_array("PRint_R", $a)',
                     );

$expected_not = array('call_user_func(\'var_DUMPi\', $a)',
                     );

?>