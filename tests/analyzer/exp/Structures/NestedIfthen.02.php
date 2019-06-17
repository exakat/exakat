<?php

$expected     = array('if(3 === \'init_elseif\') { /**/ } elseif(3 == \'thenthen\') { /**/ } else { /**/ } ', 
                      'if(3 == \'thenthen\') { /**/ } else { /**/ } '
                     );

$expected_not = array('if(2 == \'thenelse\') { /**/ } else { /**/ } ',
                      'elseif(3 == \'thenthen\') { /**/ } else { /**/ } ', 
                     );

?>