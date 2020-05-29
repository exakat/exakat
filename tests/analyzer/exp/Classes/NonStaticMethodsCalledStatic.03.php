<?php

$expected     = array('A::nonStaticMethod( )',
                     );

$expected_not = array('parent::nonStaticMethod( )',
                      'parent::staticMethod( )',
                      'static::nonStaticMethod( )',
                      'self::nonStaticMethod( )',
                      'static::staticMethod( )',
                      'self::staticMethod( )',
                      'A::staticMethod( )',
                      'static::nonStaticMethod( )',
                     );

?>