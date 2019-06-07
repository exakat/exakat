<?php

$expected     = array('$staticPropertyUnused = 5',
                      '$staticPropertyStatic = 2', 
                      '$staticPropertyxFNS = 4',
                     );

$expected_not = array('$staticPropertySelf = 1',
                      '$staticPropertyStatic = 2',
                      '$staticPropertyx = 3',
                      '$staticPropertyxFNS = 4',
                     );

?>