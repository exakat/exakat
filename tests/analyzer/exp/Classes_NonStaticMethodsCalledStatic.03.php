<?php

$expected     = array('A::nonStaticMethod( )');

$expected_not = array('A::staticMethod( )',
                      'parent::nonStaticMethod( )',
                      'parent::staticMethod( )',
                      'static::nonStaticMethod( )',
                      'static::staticMethod( )',
                      'self::nonStaticMethod( )',
                      'self::staticMethod( )',
                      );

?>