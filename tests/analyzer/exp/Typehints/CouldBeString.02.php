<?php

$expected     = array('function byRelay( ) { /**/ } ',
                      'function byPHP( ) { /**/ } ',
                      'function byPHP2( ) { /**/ } ',
                      '$a2 = \'a\'',
                      'function byDefault($a2 = \'a\') { /**/ } ',
                      'function byAtoms( ) { /**/ } ',
                      'function byAtoms2( ) { /**/ } ',
                      'function byAtoms3( ) { /**/ } ',
                      'function byTypedArg(string $a1) { /**/ } ',
                     );

$expected_not = array('function bar( ) : string { /**/ } ',
                     );

?>