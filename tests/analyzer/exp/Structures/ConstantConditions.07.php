<?php

$expected     = array('true ? $x : $y',
                      'false ? $x : $y',
                      '\TRUE ? $x : $y',
                      '\FALSE ? $x : $y',
                      'null ? $x : $y',
                      '0 ? $x : $y',
                      '99.7 ? $x : $y',
                      '"" ? $x : $y',
                      '"abc" ? $x : $y',
                      '[ ] ? $x : $y',
                      '[\'a\' => 123, \'b\' => 456] ? $x : $y',
                      '[\'a\', \'b\', \'c\'] ? $x : $y',
                      'array(\'a\', \'b\', \'c\') ? $x : $y',
                      );

$expected_not = array('$a ? $x : $y',
                     );

?>