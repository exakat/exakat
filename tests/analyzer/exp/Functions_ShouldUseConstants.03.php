<?php

$expected     = array('setlocale("1", \'ko2\')', 
                      "setlocale('1', 'ko3')", 
                      "setlocale(null, 'ko4')", 
                      "setlocale(1, 'ko1')", 
                      "setlocale(\\FILE_APPEND + LOCK_EX, 'ko6')", 
                      "setlocale(FILE_APPEND | 1, 'ko5')");

$expected_not = array();

?>