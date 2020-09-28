<?php

$expected     = array('explode(\',\', $string)[2]', 
                      'explode(\',,\', $string)',
                     );

$expected_not = array('explode(\',\', $string, 3)[2]', 
                      'explode(\',\', $string, 4)',
                     );

?>