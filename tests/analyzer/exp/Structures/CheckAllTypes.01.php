<?php

$expected     = array('if(is_array($a)) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if(is_callable($a)) { /**/ } else { /**/ } ',
                      'if(is_string($a)) { /**/ } else { /**/ } ',
                     );

?>