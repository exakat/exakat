<?php

$expected     = array('trait t { /**/ } ',
                      'function foo( ) { /**/ } ',
                      'class c { /**/ } ',
                     );

$expected_not = array('interface i { /**/ } ',
                      '@@$variable',
                     );

?>