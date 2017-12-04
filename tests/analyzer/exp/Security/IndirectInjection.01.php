<?php

$expected     = array('\'a\' . $a3',
                      '"c" . $a6',
                      '\'a\' . $a2',
                      '"c$a5"',
                      '\'a\' . $a4',
                      'foreach($a7 as $b => $c) { /**/ } ',
                     );

$expected_not = array('$a2',
                      '$b2',
                     );

?>