<?php

$expected     = array('define(<<<HR
b
HR, \'c\')',
                      'define(\'ab\', \'c\')',
                      'define(\'a\' . \'b2\', \'c\')',
                     );

$expected_not = array('define($c . \'b\', \'c\')',
                     );

?>