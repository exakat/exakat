<?php

$expected     = array('function byRelay( ) { /**/ } ',
                      'function byAtoms( ) { /**/ } ',
                      'function byTypedArg(A $a1) { /**/ } ',
                     );

$expected_not = array('function bar( ) : bool { /**/ } ',
                      'function byAtoms4( ) { /**/ } ',
                     );

?>