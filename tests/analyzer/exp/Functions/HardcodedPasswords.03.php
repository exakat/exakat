<?php

$expected     = array('[\'password\' => "secret"]', 
                      '[\'passwd\' => "secret"]', 
                      '[\'user\' => 1, \'pass\' => "secret"]'
                     );

$expected_not = array('[\'user\' => 1, PASSWORD => "secret"]',
                      '[\'user\' => 1, \'ab\' => "secret"]',
                     );

?>