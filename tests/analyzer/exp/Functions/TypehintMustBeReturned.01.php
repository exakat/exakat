<?php

$expected     = array('function foo( ) : string { /**/ } ',
                      'function foo4( ) : ?string { /**/ } ',
                     );

$expected_not = array('function foo2( ) : string { /**/ } ',
                      'function foo3( ) : ?string { /**/ } ',
                      'function foo5( ) { /**/ } ',
                     );

?>