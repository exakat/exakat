<?php

$expected     = array('function byRelay( ) { /**/ } ',
//                      'function byPHP( ) { /**/ } ',
//                      'function byPHP2( ) { /**/ } ',
//                      'function byDefault($a2 = true) { /**/ } ',
                      'function byAtoms( ) { /**/ } ',
                      'function byTypedArg(A $a1) { /**/ } ', 
                      );

$expected_not = array('function bar( ) : bool { /**/ } ',
                      'function byAtoms4( ) { /**/ } ',
                     );

?>