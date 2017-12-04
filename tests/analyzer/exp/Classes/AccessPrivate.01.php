<?php

$expected     = array('d::y( )',
                      'c::y( )',
                      'a::y( )',
                      'd::$x',
                      'c::$x',
                      'a::$x',
                     );

$expected_not = array('b::$x',
                      'b::y()',
                      'd::$xpr',
                      'c::$xpr',
                      'a::$xpr',
                     );

?>