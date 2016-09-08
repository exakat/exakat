<?php

$expected     = array('A::nonStaticMethod( )',
                      'self::nonStaticMethod( )',
                      'static::nonStaticMethod( )');

$expected_not = array('parent::nonStaticMethod( )',
                      'parent::staticMethod( )',
                      
                      'static::staticMethod( )',
                      
                      'self::staticMethod( )',
                      'A::staticMethod( )',
                      'static::nonStaticMethod( )',
                      );

?>