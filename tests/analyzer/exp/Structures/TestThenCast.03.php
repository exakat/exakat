<?php

$expected     = array('if($a == \'d\') { /**/ } ', 
                      'if($a == \'a\') { /**/ } ',
                     );

$expected_not = array('if($a == \'b\') { /**/ } ', 
                      'if($a == \'c\') { /**/ } ',
                      'if($a == \'e\') { /**/ } ',
                      'if($a == \'f\') { /**/ } ',
                     );

?>