<?php

$expected     = array('\\x::class',
                      'x::class',
                     );

$expected_not = array('b::class',
                      'self::class',
                      'static::class',
                      'parent::class',
                     );

?>