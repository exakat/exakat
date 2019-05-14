<?php

$expected     = array('\\x::A',
                      '\\x::A',
                      'x::A',
                      'x::A',
                     );

$expected_not = array('parent::A',
                      'self::A',
                      'static::A',
                     );

?>