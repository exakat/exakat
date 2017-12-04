<?php

$expected     = array('function cmpUsed($a, $b) { /**/ } ',
                      'function cmpUsedFullnspath($a, $b) { /**/ } ',
                     );

$expected_not = array('\\cmp\\b',
                     );

?>