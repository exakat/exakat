<?php

$expected     = array('$staticLocalyUsed = 1',
                      '$localyUsed = 1',
                      '$staticLocalyUsed2 = 1',
                      '$localyUsed2 = 1',
                      '$staticLocalyUsed3 = 1',
                      '$localyUsed3 = 1',
                     );

$expected_not = array('$usedInChild = 2',
                      '$staticUsedInChild = 2',
                     );

?>