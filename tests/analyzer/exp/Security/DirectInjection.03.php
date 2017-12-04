<?php

$expected     = array('shell_exec($_POST[\'yes\'])',
                      'shell_exec($_SERVER[\'QUERY_STRING\'])',
                      'shell_exec($_SERVER[\'UNKNOWN_INDEX\'])',
                     );

$expected_not = array('shell_exec($_server[\'QUERY_STRING\'])',
                      'shell_exec($_SERVER[\'DOCUMENT_ROOT\'])',
                     );

?>