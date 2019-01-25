<?php

$expected     = array('preg_match_ALL(\'a\', $a, $matches)',
                      'preg_match(\'a\', $a, $MATCHES)',
                      'preg_match(\'a\', $a, $matches)',
                     );

$expected_not = array('preg_match(\'a\', $a, $biou)',
                      'preg_match_none(\'a\', $a, $matches)',
                     );

?>