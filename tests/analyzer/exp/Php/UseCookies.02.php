<?php

$expected     = array('setrawcookie("TestCookie6", $value, time( ) + 3600, "/~rasmus/", "example.com", 1)',
                      'setrawcookie("TestCookie5", $value, time( ) + 3600)',
                      'setrawcookie("TestCookie4", $value)',
                      'setcookie("TestCookie2", $value, time( ) + 3600)',
                      'setcookie("TestCookie1", $value)',
                      'setcookie("TestCookie3", $value, time( ) + 3600, "/~rasmus/", "example.com", 1)',
                     );

$expected_not = array('setrawcookie( )',
                      'setcookie( )',
                     );

?>