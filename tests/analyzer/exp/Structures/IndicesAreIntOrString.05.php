<?php

$expected     = array('$echo[\\A\\aReal]',
                      '$echo[\\A\\anArray]',
                      '$echo[\\A\\aBoolean]',
                      '$echo[\\A\\aNull]',
                     );

$expected_not = array('$echo[\\A\\anInteger]',
                      '$echo[\\A\\aString]',
                      '$echo[\\A\\anExpression]',
                     );

?>