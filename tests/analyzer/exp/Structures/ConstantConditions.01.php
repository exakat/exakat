<?php

$expected     = array(  'if(1) { /**/ } elseif(2) { /**/ } else { /**/ } ', 
                        'elseif(2) { /**/ } else { /**/ } ',
                        'if(A === B) { /**/ } else { /**/ } ',
                        );

$expected_not = array('if($a == 2) { /**/ }',
                     );

?>