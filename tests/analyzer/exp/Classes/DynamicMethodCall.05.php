<?php

$expected     = array('call_user_func_array(array(\'C\', $b), $a)',
                      'call_user_func(array($a, $b), $a)',
                     );

$expected_not = array('call_user_func(array($a, \'B\'), $a)',
                     );

?>