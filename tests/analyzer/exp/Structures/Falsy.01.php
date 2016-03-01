<?php

$expected     = array('[   ]',
                      '[   ]',
                      'array( )',
                      'array( )',
                      '""',
                      'false',
                      'FALSE',
                      'False',
                      'NULL',
                      '0.01e5',
                      '0.01e3',
                      '0.0e5',
                      '0.01e3',
                      '0.0e15',
                      '-0.0e15',
                      '0',
                      '-0',
                      '<<<PHP
', 
                      '<<<\'PHP\'
',
                      );

$expected_not = array('[ [   ] ]',
                      '[ 12 ]',
                      'TRUE',
                      'true');

?>