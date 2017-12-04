<?php

$expected     = array('setcookie($a3b, "a")',
                      'setcookie($a4, $b4)',
                      'setcookie($a5, $b5, time( ) + 3600)',
                      'setcookie($a6, $b6, time( ) + 3600, \'/\')',
                      'setcookie($a7, $b7, time( ) + 3600, \'/\', \'domain.com\')',
                      'setcookie($a8, $b8, time( ) + 3600, \'/\', \'domain.com\', $_SERVER[\'HTTPS\'])',
                     );

$expected_not = array('setcookie($a1)',
                      'setcookie($a2, \'\')',
                      'setcookie($a3, null)',
                      'setcookie($a9, $b9, time( ) + 3600, \'/\', \'domain.com\', $_SERVER[\'HTTPS\'], 1)',
                     );

?>