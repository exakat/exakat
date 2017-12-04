<?php

$expected     = array('preg_replace("($a)", \'b\', $a)',
                      'preg_replace(\'!a!\', \'b\', $a)',
                      'preg_replace(\'#a#\', \'b\', $a)',
                      'preg_replace(\'#a#\', \'b\', $a)',
                     );

$expected_not = array('preg_replace(\'/a/\', \'b\', $a)',
                      'preg_replace(\'$a$\', \'b\', $a)',
                     );

?>