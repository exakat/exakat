<?php

$expected     = array('P::nonStaticAClass( )', );

$expected_not = array('P::staticAClass( )',
                      '\a1::nonStaticButSelfClass( )', 
                      'b::nonStaticButSelfClass( )', 
                      'A1::nonStaticButSelfClass( )'
);

?>