<?php

$expected     = array('$usedVar2',
                      '$usedVar3',
                      '$usedVar1',
                      '$usedVar',
                      '$usedDefinedVar1 = 1',
                      '$usedDefinedVar2 = 2',
                      '$usedDefinedVar = 7',
                      '$usedDefinedVar3 = 3',
                     );

$expected_not = array('$unusedVar2',
                      '$unusedVar3',
                      '$unusedVar1',
                      '$unusedVar',
                      '$unusedDefinedVar2',
                      '$unusedDefinedVar3',
                      '$unusedDefinedVar1',
                      '$unusedDefinedVar',
                     );

?>