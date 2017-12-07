<?php

$expected     = array('$staticPropertyUnused1 = 5',
                      '$staticPropertyUnused2 = 5',
                      '$staticPropertyUnused3 = 5',
                     );

$expected_not = array('$staticPropertySelf1 = 1',
                      '$staticPropertyStatic1 = 2',
                      '$staticPropertyx1 = 3',
                      '$staticPropertyxFNS1 = 4',
                      '$staticPropertySelf2 = 1',
                      '$staticPropertyStatic2 = 2',
                      '$staticPropertyx2 = 3',
                      '$staticPropertyxFNS2 = 4',
                      '$staticPropertySelf3 = 1',
                      '$staticPropertyStatic3 = 2',
                      '$staticPropertyx3 = 3',
                      '$staticPropertyxFNS3 = 4',
                     );

?>