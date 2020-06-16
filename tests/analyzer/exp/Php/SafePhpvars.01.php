<?php

$expected     = array('$_SERVER[$a]',
                      '$_SERVER[A]',
                      '$_SERVER[\'_\']',
                      '$_SERVER[\'SCRIPT_NAME\']',
                     );

$expected_not = array('$_server[\'SCRIPT_NAME\']',
                      '$_SERVER[\'script_name\']',
                     );

?>