<?php

$expected     = array('$a === null ?? $b',
                      'is_null($a) ?? $b',
                      'isset($a) ?: $b',
                      'isset($a) ?? $b',
                     );

$expected_not = array('isset($a) ? 3 : $b',
                      '$a ?: $b',
                     );

?>