<?php

$expected     = array('$staticPropertyUnused1 = 5',
                      '$staticPropertyUnused2 = 5',
                      '$staticPropertyUnused3 = 5',
                      '$staticPropertyxFNS1 = 4',
                      '$staticPropertyStatic3 = 2',
                      '$staticPropertyStatic2 = 2',
                      '$staticPropertyStatic1 = 2',
                      '$staticPropertyxFNS3 = 4',
                      '$staticPropertyxFNS2 = 4',
                      );

$expected_not = array('$staticPropertySelf1 = 1',
                      '$staticPropertyx1 = 3',
                      '$staticPropertyxFNS1 = 4',
                      '$staticPropertySelf2 = 1',
                      '$staticPropertyx2 = 3',
                      '$staticPropertySelf3 = 1',
                      '$staticPropertyx3 = 3',
                     );

?>