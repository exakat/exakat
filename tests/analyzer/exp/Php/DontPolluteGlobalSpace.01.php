<?php

$expected     = array('class C { /**/ } ',
                      'function foo( ) { /**/ } ',
                      'interface i { /**/ } ',
                      'trait t { /**/ } ',
                      'A = 1',
                      'define(\'AA\', 1)',
                      'define(\'AAb\', 1)',
                     );

$expected_not = array('class Cb { /**/ } ',
                      'function foob( ) { /**/ } ',
                      'interface ib { /**/ } ',
                      'trait tb { /**/ } ',
                      'Ab = 1',
                      'define(\'B\\AA\', 1)',
                      'define(\'B\\AAb\', 1)',
                     );

?>