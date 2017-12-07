<?php

$expected     = array('mb_ereg_replace("\\(" . $delim . ")(" . $x . ")\\is", $a3, $b, "msere")',
                      'mb_ereg_replace("\\(" . $delim . ")(" . $x . ")\\is", $a1, $b, "msre")',
                      'mb_ereg_replace("\\(" . $delim . ")(" . $x . ")\\is", $a2, $b, "mser")',
                     );

$expected_not = array('mb_eregi_replace("\\(" . $delim . ")(" . $x . ")\\es", $a, $b)',
                      'mb_eregi_replace("\\w", $a, $b)',
                     );

?>