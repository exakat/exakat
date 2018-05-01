<?php

$expected     = array('$a = $c xor other( )',
                     );

$expected_not = array('$a = $b and die(1)',
                      '$a = $c or safeExit( )',
                     );

?>