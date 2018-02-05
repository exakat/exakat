<?php

$expected     = array('$fnp = mb_strtolower($name->code2)',
                     );

$expected_not = array('$fnp1 = mb_strtolower($name->code)',
                      '$fnp2 = substr($source3, 0, $offset)',
                     );

?>