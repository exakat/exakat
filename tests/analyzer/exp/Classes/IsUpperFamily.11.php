<?php

$expected     = array('a::inAA( )',
                     );

$expected_not = array('a::inA( )',
                      'a::{$inAA}( )',
                      'a::inB( )',
                      'a::inTrait( )',
                      'a::nowhere( )',
                      'c::inC( )',
                     );

?>