<?php

$expected     = array('$unused = 2',
                     );

$expected_not = array('$used',
                      '$usedInside = 3',
                      '$usedButStatic',
                      '$usedButStatic = 4',
                     );

?>