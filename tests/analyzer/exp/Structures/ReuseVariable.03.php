<?php

$expected     = array('strtolower($b)', 
                      'strtolower($b)', 
                      'strtolower($b)', 
                      'strtolower($b)', 
                      'strtolower($b) ** strtolower($d)', 
                      'strtolower($b) >> strtolower($d)', 
                      'strtolower($b) * strtolower($c)', 
                      'strtolower($b)', 
                      'strtolower($b)'
                     );

$expected_not = array('strtolower($a)', 
                     );

?>