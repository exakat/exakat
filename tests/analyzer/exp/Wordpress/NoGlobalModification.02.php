<?php

$expected     = array('$GLOBALS[\'post\']',
                     );

$expected_not = array('$GLOBALS[\'noWPglobal\']',
                      '$GLOBALS[\'multipage\']',
                      '$wp_version',
                     );

?>