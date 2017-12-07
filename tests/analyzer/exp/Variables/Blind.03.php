<?php

$expected     = array('A::$staticVariable',
                      'D::$staticVariableD',
                      '$b',
                     );

$expected_not = array('$staticVariableA',
                      '$staticVariableD',
                      '$d',
                      'D',
                      '$a',
                      'A',
                     );

?>