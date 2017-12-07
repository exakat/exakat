<?php

$expected     = array('function is_iterable( ) { /**/ } ',
                      'function mb_scrub( ) { /**/ } ',
                     );

$expected_not = array('function mb_ord( ) { /**/ } ',
                      'function gmp_root( ) { /**/ } ',
                     );

?>