<?php

$expected     = array('preg_replace(\'#asdf)#\', \'b\', $c)',
                      'preg_replace_callback("#|&\\#40;e" . $s . "is#eimsuxADJSUXa", \'b\', $c)',
                      'preg_replace_callback("#|&\\#41;e" . $s . "is#eimsuxaADJSUX", \'b\', $c)',
                      'preg_replace_callback("#|&\\#42;e" . $s . "is#aeimsuxADJSUX", \'b\', $c)',
                      'preg_match("#|&\\#40;e${s}is#Ff", \'b\', $c)',
                      'preg_replace(\'#|&\\#40;eis#B\', \'b\', $c)',
                     );

$expected_not = array('\\preg_replace(\'(\' . $b . \'(!?=+)\' . $d . \')\', \'$g\', $j)',
                      'preg_replace_callback("#|&\\#42;e" . $s . "is#aeimsuxADJSUX", \'b\', $c)',
                      'preg_match("@|&\\#40;e${s}is@", \'b\', $c)',
                     );

?>