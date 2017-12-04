<?php

$expected     = array('$b = array_pop($a)',
                      'array_reverse($a)[0]',
                      'array_slice($a, -1)[0]',
                      'current(array_slice($a, -1))',
                     );

$expected_not = array('$a[count($a) - 1]',
                     );

?>