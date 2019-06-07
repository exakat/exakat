<?php

$expected     = array('$staticPropertyUnused = 5',
                      '$staticPropertyParent = 12',
                      '$staticPropertyx = 3',
                      '$staticPropertywFNS = 41',
                      '$staticPropertyw = 31',
                      '$staticPropertyUnused = 5',
                     );

$expected_not = array('$staticPropertyStatic = 2',
                      '$staticPropertyx = 3',
                      '$staticPropertyxFNS = 4',
                     );

?>