<?php

$expected     = array('A::nonStaticMethod( )');

$expected_not = array('parent::nonStaticMethod( )',
                      'parent::staticMethod( )',
                      
                      'static::staticMethod( )',
                      
                      'self::staticMethod( )',
                      'A::staticMethod( )',
                      'self::nonStaticMethod( )',
                      'static::nonStaticMethod( )',
                      );

?>