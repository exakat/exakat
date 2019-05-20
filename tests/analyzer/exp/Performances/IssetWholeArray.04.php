<?php

$expected     = array('isset($_GET, $_GET[\'c\'])',
                     );

$expected_not = array('$_GET[\'a\'], $_GET[\'b\']',
                      '$x[\'a\'], $x[\'b\']',
                     );

?>