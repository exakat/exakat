<?php

$expected     = array('parent::A',
                      'self::A',
                      'static::A',
                      '\\x::A',
                      '\\x::A',
                      'x::A',
                      'x::A',
                     );

$expected_not = array('parent::A',
                      'self::A',
                      'static::A',
                     );

?>