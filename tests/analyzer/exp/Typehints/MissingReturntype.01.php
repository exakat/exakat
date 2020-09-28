<?php

$expected     = array('function fooS( ) : string { /**/ } ',
                     );

$expected_not = array('function fooOK( ) : ?string { /**/ } ',
                      'function fooNone( ) { /**/ } ',
                     );

?>