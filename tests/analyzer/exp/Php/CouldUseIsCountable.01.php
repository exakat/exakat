<?php

$expected     = array('!is_array($arg4) and !$x4 instanceof \\Countable',
                      '$x3 instanceof \\Countable xor is_array($x3)',
                      'is_array($x1) or $x1 instanceof \\Countable',
                     );

$expected_not = array('$x3 instanceof \\Countable xor is_array($x3)',
                     );

?>