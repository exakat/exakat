<?php

$expected     = array('function byRelay( ) { /**/ } ',
                      'function byPHP( ) { /**/ } ',
                      'function byPHP2( ) { /**/ } ',
                      'function byAtoms( ) { /**/ } ',
                      'function byAtoms2( ) { /**/ } ',
                      'function byAtoms3( ) { /**/ } ',
                      'function byTypedArg(float $a1) { /**/ } ',
                      'function byDefault($a2 = 3.3) { /**/ } ',
                      '$a2 = 3.3',
                     );

$expected_not = array('function bar( ) : bool { /**/ } ',
                     );

?>