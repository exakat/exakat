<?php

$expected     = array('define(\'d3\', \\d)',
                      'define(\'d2\', d)',
                     );

$expected_not = array('define(\'d\', strtolower(c))',
                     );

?>