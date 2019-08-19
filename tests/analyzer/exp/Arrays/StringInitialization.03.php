<?php

$expected     = array('if(true == is_string($a)) { /**/ } ',
                      'if(($b = is_scalar($a)) === FALSE) { /**/ } ',
                      'if(is_real($a) === true) { /**/ } ',
                      'if(is_numeric($a)) { /**/ } ',
                     );

$expected_not = array('if(is_array($a)) { /**/ } ',
                     );

?>