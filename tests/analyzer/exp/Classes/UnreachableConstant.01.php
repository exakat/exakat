<?php

$expected     = array('parent::A', // Outside the class
                      'self::A',
                      'static::A',
                      '\x::A',
                      '\x::A',

                      'x::A',  // inside the class
                      'x::A',
                     );

$expected_not = array('parent::A', // Outside the class
                      'self::A',
                      'static::A',
                     );

?>