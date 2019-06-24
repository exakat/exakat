<?php

$expected     = array('$staticPropertyUnused = 5',
                      );

$expected_not = array('$usedProtectedByAbove',
                      '$usedProtectedByBelowC',
                      '$usedProtectedByBelowE',
                      '$usedProtectedByBelowF',
                      '$staticPropertyx = 3', 
                      '$staticPropertyxFNS = 4',

                      '$staticPropertyStatic = 2',
                     );

?>