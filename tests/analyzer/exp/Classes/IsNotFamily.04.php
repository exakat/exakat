<?php

$expected     = array('C::f( )',
                      'B::f( )',
                      '\\A1::f( )',
                      'A1::f( )',
                      'A1::f( )',
                      '\\A2::f( )',
                     );

$expected_not = array('self',
                      'parent',
                      'static',
                     );

?>