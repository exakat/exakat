<?php

$expected     = array('$dynamicFunction( )', 
                      '$dynamicMethod(1)', 
                      '$dynamicStaticMethod(3)',
                      '$object', 
                      '$object');

$expected_not = array('$staticProperty', 
                      'aClass');

?>