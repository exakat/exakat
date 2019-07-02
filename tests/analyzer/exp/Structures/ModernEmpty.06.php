<?php

$expected     = array('$a = array_merge($b->get(null, $m2), $b->get(null, "c/$dm"), $b->get(null, "c/$dc"))',
                     );

$expected_not = array('$a = array_merge($b->get(null, $m), $b->get(null, "c/$dm"), $b->get(null, "c/$dc"))',
                     );

?>