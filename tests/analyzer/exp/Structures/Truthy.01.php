<?php

$expected     = array('array(12)', 
                      '[12]', 
                      '[[ ]]', 
                      'array(array( ))', 
                      '<<<\'PHP\'
dd
PHP',
                       '<<<PHP
$sd$f
PHP
', 
                      '<<<PHP
sd$f
PHP
', 
                      '<<<PHP
sd$f
PHP
', 
                      '"false"', 
                      '"foo"', 
                      '"f$oo"',
                       '"true"', 
                      '-2', 
                      '2.3e5', 
                      '1', 
                      '12', 
                      '12', 
                      'TRUE', 
                      'true');

$expected_not = array();

?>