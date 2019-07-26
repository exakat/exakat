<?php

$expected     = array('array_key_exists(\'a\', $a)',
                      'array_key_exists(\'a\', $a2)',
                      'array_key_exists(\'a\', $a5)',
                      'array_key_exists(\'c\', $c5)',
                     );

$expected_not = array('array_key_exists(\'b\', $b)',
                      'array_key_exists(\'b\', $b2)',
                      'array_key_exists(\'b\', $b5)',
                     );

?>