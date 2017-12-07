<?php

$expected     = array('b::a( )',
                      'E::e( )',
                      '\\E::e( )',
                      'b::e( )',
                     );

$expected_not = array('b::d()',
                      'E::a( )',
                      '\\E::a( )',
                      'E::e( )',
                      '\\E::e( )',
                     );

?>