<?php

$expected     = array('filter_var($_GET)', 
                      'filter_input($_env)',
                      'filter_var_array($_POST[\'x\'])',
                      'filter_input_array(INPUT_GET, \'i\')',
                     );

$expected_not = array('filter_var($_env)',
                      'filter_input_var(INPUT_GET, \'b\')'
                     );

?>