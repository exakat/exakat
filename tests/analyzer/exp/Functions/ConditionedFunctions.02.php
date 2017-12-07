<?php

$expected     = array('function conditionedByY( ) { /**/ } ',
                      'function conditionedByX1( ) { /**/ } ',
                      'function conditionedByX12( ) { /**/ } ',
                      'function conditionedByX123( ) { /**/ } ',
                     );

$expected_not = array('function unconditionalFunction( ) { /**/ } ',
                      'function enveloppe( ) { /**/ } ',
                      'function ($closure) { /**/ };',
                     );

?>