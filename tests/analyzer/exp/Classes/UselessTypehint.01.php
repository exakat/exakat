<?php

$expected     = array('function __set(string $name1, $x) { /**/ } ',
                      'function __get(string $name2) { /**/ } ',
                      'function __get(?string $name6) { /**/ } ', 
                      'function __set(?string $name5, $x) { /**/ } ',
                     );

$expected_not = array('function __set($name3, array $x) { /**/ } ',
                      'function __get($name4) { /**/ } ',
                     );

?>