<?php

$expected     = array('A::nonStaticMethod( )',
                      'self::nonStaticMethod( )',
                      'self::nonStaticMethod( )');

$expected_not = array('parent::nonStaticMethod( )',
                      'parent::staticMethod( )',
                      
                      'static::staticMethod( )',
                      
                      'self::staticMethod( )',
                      'A::staticMethod( )',
                      'static::nonStaticMethod( )',
                      );

?>