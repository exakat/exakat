<?php

$expected     = array('substr($a, 1, 2)',
                      'substr($a, 1, 2)',
                      'substr($a, 1, 2)',
                      'substr($a, 1, 2)',
                      'duplicateInTwoFunctions(1, 2, 3)',
                      'duplicateInTwoFunctions(1, 2, 3)',
                     );

$expected_not = array('singleCall()',
                      'multipleCallVariousArg(1)',
                      'multipleCallVariousArg(2)',
                      'multipleCallVariousArg(3)',
                     );

?>