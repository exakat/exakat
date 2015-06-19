<?php

$expected     = array('if ($alternative) : ; endif', 
                      'for($i = 0 ; $i < 10 ; $i++) : ; endfor', 
                      'foreach($a as $b) : ; endforeach', 
                      'while ($alternative) : ; endwhile',
                      'switch ($alternative) :  /**/  endswitch');

$expected_not = array();

?>