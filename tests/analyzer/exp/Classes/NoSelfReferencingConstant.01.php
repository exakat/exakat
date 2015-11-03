<?php

$expected     = array('const C = self::C',
                      'const UPPERC = SELF::C',
                      'const CSE_C = SELF::C + 1',
);

$expected_not = array('const NORMAL_C = M_PI + 2',
                      'const NORMAL_C2 = 3 * 2');

?>