<?php

$expected     = array('$a = $b * $c * $a',
                      '$a = $b * $a',
                      '$a = $b * $c * ($d * $e * $e = $a)',
                      '$a = $b * $c * ($d * $e * $a)',
                      '$a = $b * $c * $d * $e * $a',
                      '$a = $b * $c * $d * $a',
                     );

$expected_not = array('$a = $b * $c * ( $d * $e * $e = $A)',
                      '$a = $b * $c + ( $d * $e * $e = $A)',
                     );

?>