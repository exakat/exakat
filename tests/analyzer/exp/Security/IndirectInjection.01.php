<?php

$expected     = array('\'a\' . $a2',
                      '\'a\' . $a3',
                      '\'a\' . $a4',
                      '"c" . $a6',
                      '"c$a5"',
                      'foreach($a7 as $b => $c) { /**/ } ',
                      'shell_exec(\'a\' . $a2)',
                      'shell_exec(\'a\' . $a3)',
                      'shell_exec(\'a\' . $a4)',
                      'shell_exec($a1)',
                     );

$expected_not = array('$a2',
                      '$b2',
                      '$a1',
                      '$_GET',
                     );

?>