<?php

$expected     = array('-$a + $b',
                      '-$a2 + $b2',
                     );

$expected_not = array('-($a + $b)',
                      '-$a2 + $b2 - c2',
                      '!$a instanceof Stdclass',
                      '+$a + $b - c',
                     );

?>