<?php

$expected     = array('x::e',
                      '\\x::e',
                      '\\x::$p',
                      'x::$p',
                      'x::method( )',
                      '\\x::method( )',
                     );

$expected_not = array('b::e',
                      'self::e',
                      'self::$p',
                      'b::$p',
                      'b::method( )',
                      'self::method( )',
                     );

?>