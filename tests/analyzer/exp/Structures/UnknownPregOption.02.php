<?php

$expected     = array('preg_replace(\'/^\\//a\', \'\', $url)',
                      'preg_match("|.2+/$|v", $dir)',
                      'preg_replace("/$b2^\\//a", \'\', $url)',
                      'preg_match("|$a2+/$|v", $dir)',
                      'preg_match("|" . $b2 . "+/$|v", $dir)',
                      'preg_replace("/^" . $a2 . "\\//a", \'\', $url)',
                     );

$expected_not = array('preg_match("|.+/$|", $dir)',
                     );

?>