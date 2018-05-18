<?php

$expected     = array('ini_set(\'display_errors\', 1)', 
                      'ini_set(\'display_errors\', \'false\')', 
                      'ini_set(\'display_errors\', \'on\')',
                     );

$expected_not = array('ini_set(\'display_errors\', 0)', 
                      'ini_set(\'display_errors\', false)', 
                      'ini_set(\'display_errors\')',
                     );

?>