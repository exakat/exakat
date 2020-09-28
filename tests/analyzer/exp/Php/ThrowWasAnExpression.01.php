<?php

$expected     = array('fn ( ) => throw new Exception( )',
                      '$nullableValue ?? throw new InvalidArgumentException( )',
                      '$falsableValue ?: throw new InvalidArgumentException( )',
                      '!empty($array) ? reset($array) : throw new InvalidArgumentException( )',
                      '$condition && throw new Exception( )',
                      '$condition || throw new Exception( )',
                      '$condition and throw new Exception( )',
                      '$condition or throw new Exception( )',
                     );

$expected_not = array('throw new Exception(\'standalone\')',
                     );

?>