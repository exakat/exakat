<?php

$expected     = array( ' { /**/ } ', 
                       ' { /**/ } ', 
                       ' { /**/ } ',
                     );
// Three only, not five.

$expected_not = array('if ($c) { /**/ } else { /**/ } ',
                     );

?>