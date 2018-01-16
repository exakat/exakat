<?php

$expected     = array('$unused = 2',
                      '$usedInside = 3',
                     );

$expected_not = array('$used',
                      '$usedButStatic',
                      '$usedButStatic = 4',
                     );

?>