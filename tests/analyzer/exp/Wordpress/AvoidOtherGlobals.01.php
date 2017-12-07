<?php

$expected     = array('$GLOBALS[\'my_variable\']',
                      'global $is_phone7',
                     );

$expected_not = array('$GLOBALS[\'is_gecko\']',
                      'global $currentday',
                     );

?>