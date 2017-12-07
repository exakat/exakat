<?php

$expected     = array('$a or $b',
                     );

$expected_not = array('define(\'a\', 1) or define(\'b\', 2)',
                      'define(\'a\', 3) xor $c',
                      '$d and define(\'b\', 6)',
                     );

?>