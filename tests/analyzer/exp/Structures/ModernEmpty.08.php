<?php

$expected     = array('self::$a = strtolower($b . $c)',
                     );

$expected_not = array('strtolower($b . $c)',
                      'strtolower($b0 . $c0)',
                      'self::$a2 = strtolower($b . $c)',
                      'self::$d = strtolower($b0 . $c0)',
                      'self::$a2 = strtolower($b . $c)',
                     );

?>