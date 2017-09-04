<?php

$expected     = array('$_SERVER[\'HTTP_UNKNOWN\'] . \' -l \' . $file',
                     );

$expected_not = array('$_SERVER[\'_\'] . \' -l \' . $file',
                     );

?>