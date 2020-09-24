<?php

$expected     = array('strtolower($b)',
                      'strtolower($b)',
                      'strtolower($b)',
                     );

$expected_not = array('strtolower($a)',
                      'strtolower($b) + strtolower($d)', // those are assigned
                      'strtolower($b) + strtolower($c)',
                     );

?>