<?php

$expected     = array('P::nonStaticAClass( )',
                      'A1::nonStaticButSelfClass( )',
                      'b::nonStaticButSelfClass( )',
                      '\\a1::nonStaticButSelfClass( )',
                     );

$expected_not = array('P::staticAClass( )',
                      'parent::nonStaticButSelfClass( )',
                      'self::nonStaticButSelfClass( )',
                      'static::nonStaticButSelfClass( )',
                     );

?>