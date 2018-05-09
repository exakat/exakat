<?php

$expected     = array('-$a + $b',
                      '-$a + $b - c',
                     );

$expected_not = array('-($a + $b)',
                      '!$a instanceof Stdclass',
                      '+$a + $b - c',
                     );

?>