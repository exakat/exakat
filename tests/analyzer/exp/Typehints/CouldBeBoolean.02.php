<?php

$expected     = array('function byRelay( ) { /**/ } ',
                      'function byPHP( ) { /**/ } ',
                      'function byPHP2( ) { /**/ } ',
                      '$a2 = true',
                      'function byDefault($a2 = true) { /**/ } ',
                      'function byAtoms( ) { /**/ } ',
                      'function byAtoms2( ) { /**/ } ',
                      'function byAtoms3( ) { /**/ } ',
                      'function byTypedArg(bool $a1) { /**/ } ',
                     );

$expected_not = array('function bar( ) : bool { /**/ } ',
                     );

?>