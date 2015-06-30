<?php

$expected     = array('$dynamicFunction( )', 
                      '$dynamicStaticMethod(3)', 
                      '$dynamicMethod(1)',
                      '$object', 
                      '$object');

$expected_not = array('$staticProperty', 
                      'aClass');

?>