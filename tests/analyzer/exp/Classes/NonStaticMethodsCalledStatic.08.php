<?php

$expected     = array('P::nonStaticAClass( )',
                      'A1::nonStaticButSelfClass( )',
                      'b::nonStaticButSelfClass( )',
                      '\\a1::nonStaticButSelfClass( )',
                      'self::nonStaticButSelfClass( )',
                      'static::nonStaticButSelfClass( )',
                      'self::nonStaticButSelfClaSs( )',
                      'self::nonStaticButSelfClasS( )',
                      'static::nonStaticButSelfClasS( )',
                     );

$expected_not = array('P::staticAClass( )',
                      'parent::nonStaticButSelfClass( )',
                     );

?>