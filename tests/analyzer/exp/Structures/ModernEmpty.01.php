<?php

$expected     = array('$a = strtolower($b . $c)',
                     );

$expected_not = array('strtolower($b . $c)',
                      'strtolower($b0 . $c0)',
                      '$a2 = strtolower($b . $c)',
                      '$d = strtolower($b0 . $c0)',
                     );

?>