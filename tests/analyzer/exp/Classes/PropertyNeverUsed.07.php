<?php

$expected     = array('$staticPropertyUnused = 5',
                      '$staticPropertyx = 3',
                      '$staticPropertyStatic = 2',
                      '$staticPropertyxFNS = 4',
                     );

$expected_not = array('$staticPropertyStatic = 2',
                      '$staticPropertyx = 3',
                      '$staticPropertyxFNS = 4',
                      '$staticPropertywFNS = 41',
                      '$staticPropertyw = 31',
                      '$staticPropertyParent = 12',
                     );

?>