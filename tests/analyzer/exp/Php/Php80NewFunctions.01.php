<?php

$expected     = array('function str_contains($haystack, $needle) { /**/ } ',
                      'function preg_last_error_msg( ) { /**/ } ',
                     );

$expected_not = array('preg_last_error( )',
                      'function fdiv( ) { /**/ } ',
                     );

?>