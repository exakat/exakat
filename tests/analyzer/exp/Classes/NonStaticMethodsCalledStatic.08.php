<?php

$expected     = array('P::nonStaticAClass( )',
                     );

$expected_not = array('P::staticAClass( )',
                      'parent::nonStaticButSelfClass( )',
                      'self::nonStaticButSelfClass( )',
                      'static::nonStaticButSelfClass( )',
                      'self::nonStaticButSelfClaSs( )',
                      'self::nonStaticButSelfClasS( )',
                      'static::nonStaticButSelfClasS( )',
                      'A1::nonStaticButSelfClass( )',
                      'b::nonStaticButSelfClass( )',
                      '\\a1::nonStaticButSelfClass( )',
                     );

?>