<?php

$expected     = array('$variableFunctionCall',
                      '$arrayFunctionCall[1]',
                     );

$expected_not = array('normalFunctionCall',
                      '\\NSedFunctionCall',
                      '\\a\\b\\NSedFunctionCall',
                      'methodFunctionCall',
                     );

?>