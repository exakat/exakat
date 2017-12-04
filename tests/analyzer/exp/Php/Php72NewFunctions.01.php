<?php

$expected     = array('function mb_ord( ) { /**/ } ',
                      'function stream_isatty( ) { /**/ } ',
                     );

$expected_not = array('function stream_some_function( ) { /**/ } ',
                      'function mb_chr( ) { /**/ } ',
                      'function mb_scrub( ) { /**/ } ',
                     );

?>