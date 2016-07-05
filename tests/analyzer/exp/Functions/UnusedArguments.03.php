<?php

$expected     = array('&$unusedClosureR', 
                      '$writenOnlyClosure', 
                      '$unusedUseClosure', 
                      'X &$unusedA2', 
                      '$ClassArgument', 
                      '$traitArgument', 
                      'X $unusedA1', 
                      'X $writenOnlyA1');

$expected_not = array('X $readOnly', );

?>