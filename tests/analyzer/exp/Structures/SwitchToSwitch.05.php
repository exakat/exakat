<?php

$expected     = array('if($a === 1) { /**/ } else  /**/  ',
                      'if($a === 22) { /**/ } else  /**/  ',
                      'if($a === 31) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($a === 11) { /**/ } else { /**/ } ',
                     );

?>