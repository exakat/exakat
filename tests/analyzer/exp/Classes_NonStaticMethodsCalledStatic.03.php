<?php

$expected     = array('A::nonStaticMethod( )',
                      'self::nonStaticMethod( )',
                      'static::nonStaticMethod( )',);

$expected_not = array('A::staticMethod( )',
                      'parent::nonStaticMethod( )',
                      'parent::staticMethod( )',
                      
                      'static::staticMethod( )',
                      
                      'self::staticMethod( )',
                      );

?>