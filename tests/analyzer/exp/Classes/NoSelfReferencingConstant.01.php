<?php

$expected     = array('C = self::C',
                      'UPPERC = SELF::UPPERC',
                      'CSE_C = SELF::C + 1',
                     );

$expected_not = array('NORMAL_C = M_PI + 2',
                      'NORMAL_C2 = 3 * 2',
                     );

?>