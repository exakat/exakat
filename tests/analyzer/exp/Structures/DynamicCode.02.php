<?php

$expected     = array('$x([1, 3, 6])',
                     );

$expected_not = array('strtolower(strtoupper($a))',
                      'strlen(\'a\'. \'3\')',
                      'tan(4 + 3)',
                      'cos(3 * 5)',
                      'sin(3 / 5)',
                      'asin(3 ** 5)',
                      'array_merge($a->b, $c)',
                      'array_count_values(Classe::$property)',
                      'array_sum($array[\'index\'])',
                      'sqrt($a << $b)',
                      'array_filter([1,3,4], function ($x) { return $x * $x; })',
                     );

?>