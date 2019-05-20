<?php

$expected     = array('isset($a, $a[3])',
                      'isset($a->c[$b], $a->c)',
                      'isset(A::$C[$b], A::$C)',
                      'isset($a->c[1], $a->c[1][2])',
                      'isset(A::$C[1], A::$C[1][2])',
                     );

$expected_not = array('isset($b, $a[$b])',
                      'isset($b, $a->c[$b])',
                     );

?>