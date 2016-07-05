<?php

$expected     = array('&$unusedClosureR',

                      '$writenOnlyClosure',
                      '$unusedUseClosure',

                      '&$unusedA2',

                      '$unusedA1',
                      '$writenOnlyA1',

                      '$traitArgument',
                      '$ClassArgument',
);

$expected_not = array('&$readOnly', 
                      '&$writenOnly', 
                      '&$readAndWritten',);

?>