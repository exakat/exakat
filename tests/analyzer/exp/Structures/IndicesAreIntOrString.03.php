<?php

$expected     = array('$echo[aReal]',
                      '$echo[anArray]',
                      '$echo[aBoolean]',
                      '$echo[aNull]',
                     );

$expected_not = array('$echo[anInteger]',
                      '$echo[aString]',
                      '$echo[anExpression]',
                     );

?>