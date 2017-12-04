<?php

$expected     = array('$a3 && $b && $c && $a3',
                      '$a4 && $b && $c && $d && $a4',
                      '$a5 && $b && $c && $d && $e && $a5',
                      '$a5a && $b && $c && $d && $e && $a5a',
                     );

$expected_not = array('$a6 && $b && $c && $d && $f && $g && $a6',
                      '$a && $b && $c && $d',
                     );

?>