<?php

$expected     = array('sprintf(\'%f\', $a - $b, D)',
                      'sprintf(\'%0.3f\', $a - $b, $c)',
                      'sprintf(\'The %2$s contains %1$04d monkeys\', $a)',
                      'sprintf(\'%+f\', $a - $b, $c)',
                      'sprintf(\'%1$d monkeys\', $a, $b, $c)',
                      'sprintf(\'%1$04d monkeys\', $a, $b, $c)',
                      'sprintf(\'%04d monkeys\', $a, $b, $c)',
                      'sprintf(\'The %2$s contains %1$04d monkeys\', $a, $b, $c)',
                     );

$expected_not = array('sprintf($a, "[" )',
                      'sprintf($a->b, "[" )',
                      'sprintf($a[\'b\'], "[" )',
                      'sprintf($a::B, "[" )',
                     );

?>