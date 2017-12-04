<?php

$expected     = array('preg_match("/$a $b/t", $r)',
                      'preg_match("/" . $a2 . " " . $b2 . "/t", $r)',
                     );

$expected_not = array('preg_match("$a $b", $r)',
                      'preg_match($a2 . " " . $b2, $r)',
                     );

?>