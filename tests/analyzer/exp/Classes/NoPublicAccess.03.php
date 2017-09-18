<?php

$expected     = array('$unused = 2',
                      '$unUsed = 2',
                      '$unUSed = 2',
                     );

$expected_not = array('$used',
                      '$uSed',
                      '$uSEd',
                      '$usedInside = 3',
                      '$usedButStatic',
                      '$usedButStatic = 4',
                     );

?>