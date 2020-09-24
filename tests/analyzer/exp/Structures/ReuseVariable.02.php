<?php

$expected     = array('strtolower($b)',
                      'strtolower($b)',
                      'strtolower($b)',
                     );

$expected_not = array('strtolower($b) + strtolower($d)',
                      'strtolower($a)',
                      'strtolower($b) + strtolower($c)',
                     );

?>