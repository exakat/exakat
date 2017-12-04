<?php

$expected     = array('abs($a) >= 0',
                      'strlen($a) >= 0',
                      'sizeof($a) >= 0',
                      'count($a) >= 0',
                     );

$expected_not = array('sin($a) >= 0',
                     );

?>