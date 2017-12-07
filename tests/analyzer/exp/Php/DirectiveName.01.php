<?php

$expected     = array('ini_set(\'NotADirective\', 0)',
                      '\\ini_get(\'AlsoNotADirective\')',
                     );

$expected_not = array('ini_get($a, $b)',
                      'ini_set(CONSTANTE, 3)',
                      'ini_get("$a", $b)',
                     );

?>