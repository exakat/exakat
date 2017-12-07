<?php

$expected     = array('$duplicate3->c( )',
                      '$duplicate3->c( )',
                      '$duplicate3->c( )',
                      '$duplicate->c( )',
                      '$duplicate->c( )',
                      '$duplicateInTwoFunctions->c( )',
                      '$duplicateInTwoFunctions->c( )',
                     );

$expected_not = array('$a->duplicateMethod(1,2,3)',
                      '$b->duplicateMethod(1,2,3)',
                     );

?>