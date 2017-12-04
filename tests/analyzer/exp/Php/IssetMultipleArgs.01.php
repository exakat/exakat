<?php

$expected     = array('isset($a1) && isset($b)',
                      'isset($a2) and isset($b)',
                      '!isset($a3) || !isset($b)',
                     );

$expected_not = array('isset($a) || isset($b)',
                      '!isset($a) || isset($b3)',
                      'isset($a) || isset($b4)',
                      'isset($a) || !isset($b5)',
                      '!isset($a) && !isset($b5)',
                     );

?>