<?php

$expected     = array('a4::m3( )',
                      'a4::m2( )',
                      'a4::m1( )',
                      'a4::m4( )',
                      'parent::m6( )',
                      'self::m7( )',
                      'static::m5( )',
                     );

$expected_not = array('static::m15( )',
                      'parent::m16( )',
                      'self::m17( )',
                      'static::m25( )',
                      'self::m27( )',
                     );

?>