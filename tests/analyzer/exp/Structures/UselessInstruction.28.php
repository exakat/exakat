<?php

$expected     = array('fn ($a, $b) => $a + $b',
                      'function ( ) : int { /**/ } ',
                     );

$expected_not = array('function foo( ) : int { /**/ } ',
                      '',
                     );

?>