<?php

$expected     = array('function byRelay( ) { /**/ } ',
                      'function byPHP( ) { /**/ } ',
                      'function byPHP2( ) { /**/ } ',
                      '$a2 = 3',
                      'function byTypedArg(int $a1) { /**/ } ',
                      'function byDefault($a2 = 3) { /**/ } ',
                      'function byAtoms( ) { /**/ } ',
                      'function byAtoms2( ) { /**/ } ',
                      'function byAtoms3( ) { /**/ } ',
                     );

$expected_not = array('function bar( ) : bool { /**/ } ',
                     );

?>