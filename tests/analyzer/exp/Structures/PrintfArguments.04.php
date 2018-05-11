<?php

$expected     = array('vsprintf("%04d-%02d-%02d", array(\'1988\', \'8\', \'1\', \'2\'))',
                      'vsprintf("%04d-%02d-%02d", array(\'1988\', \'8\',  ))',
                      'vsprintf("%04d-%02d-%02d", array(\'1988\', \'8\'))',
                     );

$expected_not = array('',
                      'vsprintf("%04d-%02d-%02d", array(\'1988\', \'8\', \'1\'))',
                      'vsprintf("%04d-%02d-%02d", array(\'1988\', \'8\', null))',
                     );

?>