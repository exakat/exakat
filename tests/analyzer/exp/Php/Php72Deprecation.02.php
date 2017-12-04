<?php

$expected     = array('create_function(\'$a,$b\', \'return "ln($a) + ln($b) = " . log($a * $b);\')',
                      'gmp_random(1)',
                      'each($fruit)',
                     );

$expected_not = array('gmp_random_bits( )',
                      'gmp_random_range( )',
                     );

?>