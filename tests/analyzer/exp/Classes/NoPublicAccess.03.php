<?php

$expected     = array('$unused = 2',
                      '$unUsed = 2',
                      '$unUSed = 2',
                      '$usedInside = 3',
                      '$used_inside_various_cases = 3', 
                      '$used_INSIDE_various_cases = 3',
                     );

$expected_not = array('$used',
                      '$uSed',
                      '$uSEd',
                      '$usedButStatic',
                      '$usedButStatic = 4',
                     );

?>