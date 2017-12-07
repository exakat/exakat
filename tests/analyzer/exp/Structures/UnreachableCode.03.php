<?php

$expected     = array('$a = function ( ) { /**/ } ',
                     );

$expected_not = array('function x() { /**/ }',
                      'interface i { /**/ } ',
                      'trait t { /**/ } ',
                      'class c { /**/ } ',
                     );

?>