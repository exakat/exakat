<?php

$expected     = array('A1::f( )',
                      '\\A2::f( )',
                      'A1::f( )',
                      'A1::f( )',
                      'A2::f( )',
                      '\\A1::f( )',
                      '\\A2::f( )',
                      'B::f( )',
                      'C::f( )',
                     );

$expected_not = array('foo::f( )',
                     );

?>