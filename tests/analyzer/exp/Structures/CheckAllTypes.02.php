<?php

$expected     = array('if(!is_object($a)) { /**/ } else { /**/ } ',
                      'if(is_string($a)) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if(is_null($a)) { /**/ } ',
                     );

?>