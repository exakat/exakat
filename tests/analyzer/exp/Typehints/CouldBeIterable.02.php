<?php

$expected     = array('function byRelay( ) { /**/ } ',
                      'function byTypedArg(iterable $a1) { /**/ } ',
                      'function byDefault($a2 = array( )) { /**/ } ',
                      'function byAtoms( ) { /**/ } ',
                      'function byAtoms2( ) { /**/ } ',
                      'function byAtoms3( ) { /**/ } ',
                     );

$expected_not = array('function bar( ) : bool { /**/ } ',
                     );

?>