<?php

$expected     = array('foo4::bar( )',
                      'foo3::bar( )',
                      'foo2::bar( )',
                      'foo::bar( )',
                      'self::bar( )',
                      'static::bar( )',
                     );

$expected_not = array('foo4::staticBar( )',
                      'foo3::staticBar( )',
                      'foo2::staticBar( )',
                      'foo::staticBar( )',
                      'parent::bar( )',
                      'self::staticBar( )',
                      'static::staticBar( )',
                      'parent::staticBar( )',
                     );

?>