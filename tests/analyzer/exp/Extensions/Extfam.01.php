<?php

$expected     = array('fam_monitor_directory($fam, \'/tmp\')',
                      'fam_close($fam)',
                      'fam_open(\'myApplication\')',
                     );

$expected_not = array('fam_not_a_function( )',
                     );

?>