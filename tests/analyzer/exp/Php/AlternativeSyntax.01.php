<?php

$expected     = array( 'while ($alternative) :  /**/  endwhile', 
                       'foreach ($a as $b) :  /**/  endforeach', 
                       'for($i = 0 ; $i < 10 ; $i++) :  /**/  endfor', 
                       'switch ($alternative) : /* cases */ endswitch', 
                       'if ($alternative) :  /**/  endif');

$expected_not = array('if ($nonalternative) { /**/ }');

?>