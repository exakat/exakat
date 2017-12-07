<?php

$expected     = array('static::c1',
                      'self::c1',
                      'parent::c1',
                      'static::m1( )',
                      'self::m1( )',
                      'parent::m1( )',
                      'static::$p1',
                      'self::$p1',
                      'parent::$p1',
                     );

$expected_not = array('static::c2',
                      'static::m2( )',
                      'self::m2( )',
                      'static::$p2',
                      'self::$p2',
                      'self::c2',
                      'parent::c2',
                      'parent::$p2',
                      'parent::m2( )',
                     );

?>