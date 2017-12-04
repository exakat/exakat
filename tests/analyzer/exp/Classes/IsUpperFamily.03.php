<?php

$expected     = array('self::inAA( )',
                     );

$expected_not = array('self::inA( )',
                      'self::inB( )',
                      'self::inTrait( )',
                      'self::nowhere( )',
                      '\\c::inC( )',
                     );

?>