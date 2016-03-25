<?php

$expected     = array('$a & $b ?? $c', 
                      '$a + $b ?? $c', 
                      '$a * $b ?? $c', 
                      '$a ** $b ?? $c', 
                      '$a . $b ?? $c');

$expected_not = array('$b[$c] ?? $d', 
                      );

?>