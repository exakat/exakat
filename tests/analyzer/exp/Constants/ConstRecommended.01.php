<?php

$expected     = array('define(\'g\', ab)',
                      'define(\'j\', \\ab)',
                      'define(\'k\', \\a\\ab)',
                      'define(\'h\', a::C)',
                      'define(\'a\', 2)',
                      'define(\'b\', 2.1)',
                      'define(\'c\', "3")',
                      'define(\'d\', "true")',
                      'define(\'e\', -1)',
                      'define(\'f\', \'true\')',
                      'define(\'i\', true)',
                      'define(\'A\', "a" . "b")',
                     );

$expected_not = array('define(\'B\', "a $b c")',
                      'define(\'C\', $a)',
                     );

?>