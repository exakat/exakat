<?php

$expected     = array('$usedProtectedByAbove',
                      '$usedProtectedDefaultedByAbove = 3',
                      '$usedStaticProtectedDefaultedByAbove = 1',
                     );

$expected_not = array('$unusedStaticProtectedDefaulted = 2',
                      '$unusedProtectedDefaulted = 4',
                      '$unusedProtected',
                     );

?>