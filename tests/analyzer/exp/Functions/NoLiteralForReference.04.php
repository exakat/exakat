<?php

$expected     = array('fn &($a1) => $a1 *= 2',
                      'fn &(&$a2) => $a2 * 2',
                      'fn &(&$a4) => ($a4 + 1)',
                      'fn &(&$a5) => rand(0, 1) ? $a5 : ($a5)',
                     );

$expected_not = array('fn &(&$a3) => ($a3)',
                     );

?>