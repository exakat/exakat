<?php

$expected     = array('global $x, $$x, ${y[2]}, $$f, $$foo->bar, ${$foo->bar}',
                     );

$expected_not = array('global $x, $bx, $cx, $dx',
                     );

?>