<?php

$expected     = array('isset($b) && empty($b)',
                      'isset($a) && !empty($a)',
                     );

$expected_not = array('isset($c) || empty($c)',
                      'isset($e) && empty($d)',
                     );

?>