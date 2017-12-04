<?php

$expected     = array('echo print_r($a, "1")',
                      'echo print_r($a, "abc")',
                      'echo print_r($a, \'1\')',
                      'echo print_r($a, true)',
                      'echo print_r($a, 1)',
                     );

$expected_not = array('Classe::var_dump($a)',
                     );

?>