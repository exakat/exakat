<?php

$expected     = array('ini_get(\'session.GC_maxlifetime\')',
                      'ini_get(\' session.gc_maxlifetime\')',
                      'ini_get(\'session.gc_maxlifetime \')',
                      'ini_get(\'session.gc_maxlifetime\')',
                     );

$expected_not = array('get_include_path(\'a\')',
                     );

?>