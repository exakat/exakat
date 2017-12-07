<?php

$expected     = array('$usedProtected2',
                      '$usedProtected3',
                      '$usedProtected1',
                      '$usedProtected',
                      '$usedDefinedProtected1 = 1',
                      '$usedDefinedProtected2 = 2',
                      '$usedDefinedProtected = 7',
                      '$usedDefinedProtected3 = 3',
                     );

$expected_not = array('$unusedProtected2',
                      '$unusedProtected3',
                      '$unusedProtected1',
                      '$unusedProtected',
                      '$unusedDefinedProtected2',
                      '$unusedDefinedProtected3',
                      '$unusedDefinedProtected1',
                      '$unusedDefinedProtected',
                     );

?>