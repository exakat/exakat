<?php

$expected     = array('static $staticPropertyUnused = 5');

$expected_not = array('static $staticPropertyStatic = 2', 
                      'static $staticPropertyx = 3', 
                      'static $staticPropertyxFNS = 4', 
                      'static $staticPropertywFNS = 41', 
                      'static $staticPropertyw = 31'
);

?>