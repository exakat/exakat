<?php

$expected     = array('$staticPropertyUnused = 5',
                      '$staticPropertyStatic = 2',
                      '$staticPropertyx = 3', 
                      '$staticPropertyxFNS = 4',
                      );

$expected_not = array('$usedProtectedByAbove',
                      '$usedProtectedByBelowC',
                      '$usedProtectedByBelowE',
                      '$usedProtectedByBelowF',
                     );

?>