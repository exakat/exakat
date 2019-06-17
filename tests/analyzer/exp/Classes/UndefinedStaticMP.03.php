<?php

$expected     = array('static::$h1',
                      'static::$h',
                      'self::$g1',
                      'self::$g',
                      'static::BE($ce[\'de\'])',
                      'self::B($c[\'d\'])',
                     );

$expected_not = array('self::$b($c[\'d\'])',
                      'static::$be($ce[\'de\'])',
                      'static::${bef}',
                      'self::$b($c[\'d\'])',
                      'static::$be($ce[\'de\'])'
                     );

?>