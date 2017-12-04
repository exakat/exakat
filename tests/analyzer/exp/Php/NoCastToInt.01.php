<?php

$expected     = array('(int) (0.14 + $c)',
                      '(int) (0.13 + 0.7)',
                      '(int) (0.12 + 0.3)',
                     );

$expected_not = array('(int) 0.1',
                      '(int) 0.11 + 0.3',
                      '(int) $o->p',
                     );

?>