<?php

$expected     = array('H::{$d}( )',
                      '$c1::{$d1}( )',
                      '$c2::{$d2}( )',
                      '$a::$bar( )',
                      '$de::{$d}( )',
                      '$a::bar( )',
                      '$a::$bar( )',
                      'foo( )::a( )',
                     );

$expected_not = array('C::$a[$b]',
                     );

?>