<?php

$expected     = array('$staticPropertyUnused = 5',
                      '$staticPropertyParent = 12',
                     );

$expected_not = array('$staticPropertyStatic = 2',
                      '$staticPropertyx = 3',
                      '$staticPropertyxFNS = 4',
                      '$staticPropertywFNS = 41',
                      '$staticPropertyw = 31',
                     );

?>