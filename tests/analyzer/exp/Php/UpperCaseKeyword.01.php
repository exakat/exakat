<?php

$expected     = array('WHILE ($c++) { /**/ } ',
                      'DO { /**/ } WHILE($a++ AND $b++)',
                      'CLASS x { /**/ } ',
                      'CLASS x IMPLEMENTS a { /**/ } ',
                      'FOREACH($a AS $b) { /**/ } ',
                      '$a++ AND $b++',
                     );

$expected_not = array('class x2 { /**/ } ',
                      'foreach($a2 as $b2) { /**/ }',
                     );

?>