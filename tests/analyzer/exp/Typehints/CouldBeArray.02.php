<?php

$expected     = array('function byRelay( ) { /**/ } ',
                      'function byPHP( ) { /**/ } ',
                      'function byPHP2( ) { /**/ } ',
                      '$a2 = array( )',
                      'function byDefault($a2 = array( )) { /**/ } ',
                      'function byAtoms( ) { /**/ } ',
                      'function byAtoms2( ) { /**/ } ',
                      'function byAtoms3( ) { /**/ } ',
                      'function byTypedArg(array $a1) { /**/ } ',
                     );

$expected_not = array('function bar( ) : array { /**/ } ',
                     );

?>