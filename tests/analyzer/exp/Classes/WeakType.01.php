<?php

$expected     = array( 'if(is_null($a4)) { /**/ } else { /**/ } ', 
                       'if(!is_null($a3)) { /**/ } ', 
                       'if($a2 === null) { /**/ } else { /**/ } ', 
                       'if($a1 !== null) { /**/ } '
                     );

$expected_not = array('if(is_null($a5)) { /**/ } else { /**/ } ', 
                     );

?>