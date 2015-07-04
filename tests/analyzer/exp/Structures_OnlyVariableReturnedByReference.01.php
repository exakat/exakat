<?php

$expected     = array('strtolower(self::y)',
                      'array(1)',
                      '1',
                      '__DIR__',
                      'true',
                      '@self::$x',
);

$expected_not = array('$x',
                      '$this->$x',
                      'x::$x',
                      '$x[3]',
                      '( $y)');

?>