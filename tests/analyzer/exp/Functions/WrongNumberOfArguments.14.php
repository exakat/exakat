<?php

$expected     = array('A',
                      'A( )',
                      'A($a, $b)',
                      'new class ( ) { /**/ } ',
                      'new class ($a, $b) { /**/ } ',
                     );

$expected_not = array('A($a)',
                      'new class ($a) { /**/ } ',
                     );

?>