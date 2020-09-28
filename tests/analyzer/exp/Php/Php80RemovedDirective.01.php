<?php

$expected     = array('\\ini_get(\'track_errors\')',
                      'ini_set(\'track_errors\', 1)',
                     );

$expected_not = array('ini_restore(\'track_errors\')',
                     );

?>