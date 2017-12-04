<?php

$expected     = array('$C . "asdf$c"',
                     );

$expected_not = array('"b${$c.$d}"',
                      '$C."asdg"',
                      '"asdf$d"',
                     );

?>