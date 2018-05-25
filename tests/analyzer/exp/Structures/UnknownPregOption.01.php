<?php

$expected     = array('preg_replace(\'#|&\\#40;eis#B\', \'b\', $c)',
                      'preg_match("#|&\\#40;e${s}is#Ff", \'b\', $c)',
                      'preg_replace_callback("#|&\\#41;e" . $s . "is#eimsuxaADJSUX", \'b\', $c)',
                      'preg_replace_callback("#|&\\#42;e" . $s . "is#aeimsuxADJSUX", \'b\', $c)',
                      'preg_replace_callback("#|&\\#40;e" . $s . "is#eimsuxADJSUXa", \'b\', $c)',
                     );

$expected_not = array('\\preg_grep(\'(\' . $a . \'(!?=+)\' . $c . \')sie\', \'$f\', $i)',
                      '\\preg_replace(\'(\' . $b . \'(!?=+)\' . $d . \')SUD\', \'$g\', $j)',
                      '\\preg_replace(\'(\' . $b . \'(!?=+)\' . $d . \')SUD\', \'$g\', $j)',
                      '\\preg_grep(\'(\' . $a . \'(!?=+)\' . $c . \')sie\', \'$f\', $i)',
                     );

?>