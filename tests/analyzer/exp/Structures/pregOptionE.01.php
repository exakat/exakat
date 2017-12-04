<?php

$expected     = array('preg_replace(\'/A\' . $x . \'H/eximU\', \'B\', $str)',
                      'preg_replace("/A$x I/eximU", \'B\', $str)',
                      'preg_replace(\'{^\\xEF\\xBB\\xBF|\\x1A}ie\', \'\', $text2)',
                      'preg_replace(\'[^\\xEF\\xBB\\xBF|\\x1A]ie\', \'\', $text2)',
                      'preg_replace(\'/AAAF/mixe\', \'B\', $str)',
                      'preg_replace(\'~AAAC~e\', \'B\', $str)',
                      'preg_replace(\'/AAAG/eximU\', \'B\', $str)',
                      'preg_replace(\'(^\\xEF\\xBB\\xBF|\\x1A)ie\', \'\', $text2)',
                      'preg_replace(\'/AAAD/ei\', \'B\', $str)',
                     );

$expected_not = array('preg_replace(\'~AAAA~\', \'B\', $str)',
                      'preg_replace(\'~AAAB~i\', \'B\', $str)',
                     );

?>