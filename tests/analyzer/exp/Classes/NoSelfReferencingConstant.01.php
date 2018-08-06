<?php

$expected     = array('C = self::C',
                      'UPPERC = SELF::UPPERC',
                      'UPPERE = \\a::UPPERE + C',
                      'UPPERD = a::UPPERD + 2',
                     );

$expected_not = array('NORMAL_C = M_PI + 2',
                      'NORMAL_C2 = 3 * 2',
                      'CSE_C = SELF::C + 1',
                     );

?>