<?php

$expected     = array('if(3 == \'elsethen\') { /**/ } else { /**/ } ',
                      'if(3 == \'elseelse\') { /**/ } else { /**/ } ',
                      'if(3 == \'thenthen\') { /**/ } else { /**/ } ',
                      'if(3 == \'thenelse\') { /**/ } else { /**/ } ',
                      'if(3 == \'elseelse2\') { /**/ } else { /**/ } ',
                     );

$expected_not = array('if(1) { /**/ } else { /**/ } ',
                      'if(2 == \'then\') { /**/ } else { /**/ } ',
                      'if(2 == \'else\') { /**/ } else { /**/ } ',
                      'if(2 == \'thenelse\') { /**/ } else { /**/ } ',
                     );

?>