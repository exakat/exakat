<?php

$expected     = array('if($a === 1) { /**/ } elseif($a === 2) { /**/ } elseif($a === 3) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($a === 10) { /**/ } else { /**/ } ',
                      'if($a === 11) { /**/ } elseif($a === 21) { /**/ } else { /**/ } ',
                     );

?>