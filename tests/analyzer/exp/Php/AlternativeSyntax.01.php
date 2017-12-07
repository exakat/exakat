<?php

$expected     = array('foreach($a as $b) :  /**/  endforeach',
                      'switch ($alternative) : /**/  endswitch',
                      'if($alternative) :   /**/   endif',
                      'while ($alternative) :  /**/  endwhile',
                      'for($i = 0 ; $i < 10 ; $i++) :  /**/  endfor',
                     );

$expected_not = array('if ($nonalternative) { /**/ }',
                     );

?>