<?php

$expected     = array('mb_eregi("/(" . $delim . ")(" . $x . ")/es", $a, $b)',
                      'preg_replace("/({$delim})(" . $x . ")/ie", $a, $b)');

$expected_not = array('preg_replace("$delim/({})(".$x.")/ie", $a, $b);');

?>