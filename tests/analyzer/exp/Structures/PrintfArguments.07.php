<?php

$expected     = array('printf(A, $a1, $a2, $a3)',
                      'printf(A, $a1)',
                      'sprintf(x::B, $a1)',
                      'sprintf(x::B, $a1, $a2, $a3)',
                     );

$expected_not = array('sprintf(A, $a1)',
                      'printf(x::B, $a1)',
                     );

?>