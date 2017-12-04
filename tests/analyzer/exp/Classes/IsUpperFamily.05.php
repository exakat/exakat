<?php

$expected     = array('static::inAA( )',
                     );

$expected_not = array('static::inA( )',
                      'static::inB( )',
                      'static::inTrait( )',
                      'static::nowhere( )',
                      'c::inC( )',
                     );

?>