<?php

$expected     = array('define(\'c\\b\\a\', 3)',
                      'define(\'c\\d\', 1)',
                     );

$expected_not = array('define(\'c\\d\\a\', 4)',
                      'define(\'a\', 5)',
                      'define(\'b\', 2)',
                     );

?>