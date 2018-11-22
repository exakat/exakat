<?php

$expected     = array('\'X-XSS-PROTECTION\' => \'0\'', 
                      '\'X-Xss-Protection\' => \'0\'', 
                      '\'x-xss-protection\' => \'0\'', 
                      'header(\'X-Xss-Protection\', \'0\')', 
                      'header(\'x-xss-protection\', \'0\')', 
                      'HEADER(\'X-XSS-PROTECTION\', \'0\')', 
                      '\'x-xss-protection: 0\'', 
                      '\'X-XSS-PROTECTION: 0\'', 
                      '\'X-Xss-Protection: 0\'',
                     );

$expected_not = array('header(\'X-Xss-: 0\')',
                      'header(\'X-Xss-Protection\', \'1\')',
                      'header([1, \'0\', 2 => 3, \'X-Xss-Protection\'])',
                     );

?>