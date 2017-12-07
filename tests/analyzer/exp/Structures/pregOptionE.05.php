<?php

$expected     = array('mb_eregi_replace("/(" . $delim . ")(" . $x . ")/es", $a, $b, \'e\')',
                      'preg_replace("/({$delim})(" . $x . ")/ie", $a, $b)',
                     );

$expected_not = array('preg_replace("$delim/({})(".$x.")/ie", $a, $b);',
                     );

?>