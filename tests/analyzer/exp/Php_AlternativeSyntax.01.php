<?php

$expected     = array('if ( $alternative) { /**/ } ', 
                      'switch ($alternative) Sequence Case Default', 
                      'while ($alternative) $y++',
                      'for($i = 0 ; $i < 10 ; $i++) $y++', 
                      'foreach($a as $b)$y++');

$expected_not = array();

?>