<?php

$expected     = array('static::C2(2)',
                      'self::C2(1)',
                      '$a::C2(5)',
                      '$this::C2(4)',
                      'parent::C2(3)',
                      'x::C2(6)',
                      '\\x::C2(7)',
                      '$a->c( )',
                      '$this->c( )',
                     );

$expected_not = array('$b::C2(7)',
                      '$c::C2(7)',
                      '$c->c( )',
                      '$b->c( )',
                     );

?>