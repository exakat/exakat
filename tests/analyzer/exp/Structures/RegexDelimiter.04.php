<?php

$expected     = array('preg_replace(\'$a$\', $a, $b)',
                     );

$expected_not = array('preg_replace(\'/a/\', $a, $b)',
                      'preg_replace(\'\', $a, $b)',
                     );

?>