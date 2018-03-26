<?php

$expected     = array('array(\'A\', \'B\', \'C\', \'D\', \'E\', \'F\',  )',
                      'array(\'A\', \'B\', \'C\', \'D\', \'W\', \'E\',  )',
                      'array(\'A\', \'B\', \'D\', \'C\', \'E\', \'F\',  )',
                      'array(\'A\', \'C\', \'B\', \'D\', \'W\', \'E\')',
                     );

$expected_not = array('array(\'F\', \'R\', \'D\', \'E\')',
                      'array(\'Return\', \'Continue\', \'Break\', \'Exit\')',
                      'array(\'A\', \'B\', \'C\', \'D\', \'E\', )',
                     );

?>