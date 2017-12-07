<?php

$expected     = array('$GLOBALS[\'usedOnce1\']',
                     );

$expected_not = array('$GLOBALS[\'unusedGlobal2\']',
                      '$unusedGlobal1',
                      '$unusedGLobal2',
                      '$unusedGlobal',
                      '$GLOBALS[\'usedTwicegG\']',
                      '$GLOBALS[\'usedTwicegG\']',
                      '$GLOBALS[\'usedTwiceGg\']',
                      '$GLOBALS[\'usedTwiceGg\']',
                      '$usedOnce2',
                     );

?>