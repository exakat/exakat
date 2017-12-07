<?php

$expected     = array('parse_str($a)',
                     );

$expected_not = array('$o->parse_str($c)',
                      'A::parse_str($d)',
                      'parse_str($a, $b)',
                     );

?>