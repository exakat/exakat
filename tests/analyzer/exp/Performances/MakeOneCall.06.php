<?php

$expected     = array('$c->d = str_replace($a1, $b1, $a)',
                      '$c->d = str_replace($a2, $b2, $c->d)',
                     );

$expected_not = array('$c->d = str_replace($a2, $b2, $c->d)',
                      'str_replace($a3, $b3, $c->d)',
                      'str_replace($a4, $b4, $ce)',
                      'str_replace($a5, $b5, $ce)',
                     );

?>