<?php

$expected     = array('\\X\\B\\ClassName::class',
                      '\\NS\\ClassName::class',
                      '\\NS1\\ClassName1::class',
                      'NS1\\ClassName1::class',
                      'ClassName1::class',
                      'X::class',
                      'parent::class',
                      'static::class',
                      'self::class',
                     );

$expected_not = array('ClassName::x( )',
                     );

?>