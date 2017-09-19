<?php

$expected     = array('$unused = 2',
                      '$usedButWrongClass = 4',
                      '$usedInside = 3',
                     );

$expected_not = array('$usedByX = 1',
                      '$usedByXFQN = 1',
                      );

?>