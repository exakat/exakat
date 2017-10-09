<?php

$expected     = array('define(\'e\', 1)',
                     );

$expected_not = array('define($a, 2)',
                      'define($c, $b)',
                      'define(\'b\', $d)',
                     );

?>