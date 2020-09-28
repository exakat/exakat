<?php

$expected     = array('function barI( ) : int { /**/ } ',
                      'function barS( ) : string { /**/ } ',
                     );

$expected_not = array('function bar( ) { /**/ } ',
                      'function barSI( ) : string { /**/ } ',
                     );

?>