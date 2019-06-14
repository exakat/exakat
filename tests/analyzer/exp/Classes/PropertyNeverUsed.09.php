<?php

$expected     = array('$staticPropertyUnused = 5',
                      '$staticPropertyx = 3',
                      '$staticPropertyParent = 12', 
                      '$staticPropertyxFNS = 4', 
                      '$staticPropertyStatic = 2',
                      );

$expected_not = array('$staticPropertyStatic = 2',
                      '$staticPropertyxFNS = 4',
                      '$staticPropertywFNS = 41',
                      '$staticPropertyw = 31',
                     );

?>