<?php

$expected     = array( 'WHILE ($c++)  { /**/ } ', 
                       'CLASS x { /**/ } ', 
                       'CLASS x IMPLEMENTS a { /**/ } ',
//                       'class x4 EXTENDS b { /**/ } ', // Extends and implements are not preserved ATM
                       'FOREACH($a AS $b) { /**/ } ',
                       '$a++ AND $b++',
                       'DO { /**/ } WHILE while ($a++ AND $b++)');

$expected_not = array('class x2 { /**/ } ', 
                      'foreach($a2 as $b2) { /**/ }');

?>