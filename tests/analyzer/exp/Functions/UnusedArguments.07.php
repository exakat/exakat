<?php

$expected     = array('function __construct($a = 3) { /**/ } ', 
                      'function __get($a) { /**/ } ', 
                      'function __set($a, $C) { /**/ } ',
                     );

$expected_not = array('function __set($a, $b) { /**/ } ',
                     );

?>