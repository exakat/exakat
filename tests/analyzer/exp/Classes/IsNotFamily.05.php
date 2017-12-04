<?php

$expected     = array('C::f( )',
                      '\\A2::f( )',
                      'A1::f( )',
                     );

$expected_not = array('B::f( )',
                      'self',
                      'parent',
                      'static',
                     );

?>