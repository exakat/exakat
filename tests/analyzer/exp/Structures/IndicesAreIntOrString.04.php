<?php

$expected     = array('$echo[x::aReal]',
                      '$echo[x::anArray]',
                      '$echo[x::aBoolean]',
                      '$echo[x::aNull]',
                     );

$expected_not = array('$echo[x::anInteger]',
                      '$echo[x::aString]',
                      '$echo[x::anExpression]',
                     );

?>