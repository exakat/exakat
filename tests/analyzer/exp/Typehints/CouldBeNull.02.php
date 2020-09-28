<?php

$expected     = array('function byRelay( ) { /**/ } ',
                      'function byPHP( ) { /**/ } ',
                      'function byPHP2( ) { /**/ } ',
                      '$a2 = null',
                      'function byTypedArg(?int $a1) { /**/ } ',
                      'function byDefault($a2 = null) { /**/ } ',
                      'function byAtoms( ) { /**/ } ',
                      'function byAtoms2( ) { /**/ } ',
                     );

$expected_not = array('function bar( ) : ?int { /**/ } ',
                     );

?>