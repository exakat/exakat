<?php

$expected     = array('preg_match_all("[$a]", \'b\', $a)',
                      'preg_match_all(\'!a!\', \'b\', $a)',
                      'preg_match_all(\'&a&\', \'b\', $a)',
                      'preg_match_all(\'#a#\', \'b\', $a)',
                     );

$expected_not = array('preg_match_all(\'/a/\', \'b\', $a)',
                      'preg_match_all(\'$a$\', \'b\', $a)',
                     );

?>