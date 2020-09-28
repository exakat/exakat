<?php

$expected     = array('function foof($a,  ) { /**/ } ',
                      'function ($ac, $b = 1,  ) { /**/ } ',
                      'function foo($a, A $b = null,  ) { /**/ } ',
                      'function __construct($a, A $b = null,  ) { /**/ } ',
                      'function foot($a, A $b = null,  ) { /**/ } ',
                      'fn ($afn, &$b,  ) => rand( )',
                     );

$expected_not = array('bar($c,  )',
                      'A\\bar($c,  )',
                      'list($a, $b,  )',
                      'list($a, , $b,  )',
                     );

?>