<?php

$expected     = array('&$unusedClosureR', 
                      '$writenOnlyClosure', 
                      '$unusedUseClosure', 
                      'X &$unusedA2 = null', 
                      '$ClassArgument', 
                      '$traitArgument', 
                      'X $unusedA1 = null', 
                      'X $writenOnlyA1 = null');

$expected_not = array('X $readOnly = null', );

?>