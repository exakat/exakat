<?php

$expected     = array('$staticPropertyUnused = 5',
                      '$staticPropertyStatic = 2',
                      );

$expected_not = array('$usedProtectedByAbove',
                      '$usedProtectedByBelowC',
                      '$usedProtectedByBelowE',
                      '$usedProtectedByBelowF',
                      '$staticPropertyx = 3', 
                      '$staticPropertyxFNS = 4',
                     );

?>