<?php

$expected     = array('if ( $alternative) :  /**/  endif', 
                      'switch ($alternative) :  /**/  endswitch',
                      'while ($alternative) : $y++ endwhile',
                      'for($i = 0 ; $i < 10 ; $i++) : $y++ endfor', 
                      'foreach($a as $b) : $y++ endforeach');

$expected_not = array();

?>