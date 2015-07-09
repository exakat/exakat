<?php

$expected     = array('if ($alternative) : ; endif', 
                      'for($i = 0 ; $i < 10 ; $i++) : $y++ endfor', 
                      'foreach($a as $b) : $y++ endforeach',
                      'while ($alternative) : ; endwhile',
                      'switch ($alternative) :  /**/  endswitch');

$expected_not = array();

?>