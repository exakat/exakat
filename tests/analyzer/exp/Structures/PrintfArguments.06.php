<?php

$expected     = array('sprintf(" a %s %s ", $a1)',
                      'printf(" a %s %s ", $a1)',
                     );

$expected_not = array('sprintf(" a $a %s %s ", $a1, $a2, $a3)',
                      'printf(" a $a %s %s ", $a1, $a2, $a3)',
                      'sprintf(" a %s %s ", $a1)',
                      'printf(" a %s %s ", $a1)',
                      'printf(" a $a %s %s ", $a1, $a2)',
                     );

?>