<?php

$expected     = array('if($b1 === true) { /**/ } else { /**/ } ',
                      'if($b2 === true) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if(!$a !== false) { /**/ } else { /**/ } ',
                      'if(!$e !== false) { /**/ } else { /**/ } ',
                     );

?>