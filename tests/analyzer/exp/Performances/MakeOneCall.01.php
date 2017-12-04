<?php

$expected     = array('$c = preg_replace_callback($a1, $b1, $a)',
                     );

$expected_not = array('$a = preg_replace_callback($a1, $b1, $c2)',
                      '$a = preg_replace_callback($a1, $b1, $c3)',
                     );

?>