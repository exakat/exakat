<?php

$expected     = array('P::nonStaticAClass( )',
                      'A1::nonStaticButSelfClass( )',
                      'static::nonStaticButSelfClass( )',
                      'b::nonStaticButSelfClass( )', 
                      '\a1::nonStaticButSelfClass( )',
                      'parent::nonStaticButSelfClass( )', 
                      'self::nonStaticButSelfClass( )'
 );

$expected_not = array('P::staticAClass( )');

?>