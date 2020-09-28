<?php

$expected     = array('strtolower($b)',
                      'strtolower($b)',
                      'strtolower($b)',
                     );

$expected_not = array('strtolower($a)',
                      'strtolower($b) + strtolower($d)',
                      'strtolower($b) + strtolower($c)',
                     );

?>