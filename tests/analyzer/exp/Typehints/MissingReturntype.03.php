<?php

$expected     = array('function barS( ) : string { /**/ } ',
                      'function barI( ) : int { /**/ } ',
                     );

$expected_not = array('function bar( ) { /**/ } ',
                      'function barSI( ) : string { /**/ } ',
                     );

?>