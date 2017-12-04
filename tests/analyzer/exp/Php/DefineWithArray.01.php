<?php

$expected     = array('define(\'c\', [3, 4])',
                      'define(\'e\', array(13, 14))',
                     );

$expected_not = array('define(\'f\', 1)',
                      'define(\'d\', join(\',\', [3, 4]))',
                     );

?>