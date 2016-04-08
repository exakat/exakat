<?php

$expected     = array('preg_replace(\'#src=.*?(?:(?:alert|prompt|confirm|eval)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#sie\', \'\', $x)', 
                      '\preg_replace(\'(\' . $b . \'(!?=+)\' . $d . \')e\', \'$g\', $j)',
                      'preg_replace(\'#|&\#40;eis#esi\', \'b\', $c)', 
                      'preg_replace(\'#|&\#40;eis#sei\', \'b\', $c)', 
                      'preg_replace(\'#|&\#40;eis#sie\', \'b\', $c)', 
                      'preg_replace("#|&\#40;e$sis#sei", \'b\', $c)', 
                      'preg_replace("#|&\#40;e$sis#sie", \'b\', $c)', 
                      'preg_replace("#|&\#40;e$sis#esi", \'b\', $c)', 
                      'preg_replace("#|&\#40;e" . $s . "is#sei", \'b\', $c)', 
                      'preg_replace("#|&\#40;e" . $s . "is#esi", \'b\', $c)', 
                      'preg_replace("#|&\#40;e" . $s . "is#sie", \'b\', $c)');
                    
$expected_not = array('preg_replace(\'#src=.*?(?:(?:alert|prompt|confirm|eval)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si\', \'\', $x)', 
                      'preg_replace("#|&\#40;e" . $s . "is#sei", \'b\', $c)', 
                      'preg_replace("#|&\#40;e$sis#esi", \'b\', $c)', 
                      'preg_replace(\'#|&\#40;eis#si\', \'b\', $c)', 
                      'preg_replace("#|&\#40;e$sis#sei", \'b\', $c)', 
                                            );

?>