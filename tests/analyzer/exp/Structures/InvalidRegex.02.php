<?php

$expected     = array('preg_replace(\'/5\' . X::Y . \'/\', \'\', \'abc\')',
                     );

$expected_not = array('preg_match(\'#^\' . $pattern . \'$#\' . $flags, \'abc\')',
                      'preg_replace(\'/1\' . preg_quote($m[0][$i], \'/\') . \'/\', \'\', \'abc\')',
                      'preg_replace(\'/2\' . $a->b . \'/\', \'\', \'abc\')',
                      'preg_replace(\'/3\' . $a[\'b\'] . \'/\', \'\', \'abc\')',
                      'preg_replace(\'/4\' . $a{\'b\'} . \'/\', \'\', \'abc\')',
                      'preg_replace(\'/6$a[b-d]/\', \'\', \'abc\')',
                      'preg_match(\'/8\' . addcslashes($this->list_sep, \'/\'\') . \'/i\', \'abc\')',
                     );

?>