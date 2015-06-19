<?php

$expected     = array('$GLOBALS[\'usedOnce1\']',
                      '$usedOnce2',
);

$expected_not = array('$GLOBALS[\'unusedGlobal2\']',
                      '$unusedGlobal1',
                      '$unusedGLobal2',
                      '$unusedGlobal',
);

?>