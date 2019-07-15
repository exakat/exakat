<?php

$expected     = array('if($a === 32) { /**/ } else  /**/  ',
                      'if($a === 11) { /**/ } else  /**/  ',
                      'if($a === 1) { /**/ } else  /**/  ',
                      'if($a === 31) { /**/ } else  /**/  ',
                     );

$expected_not = array('if($a === 21) { /**/ } else { /**/ } ',
                     );

?>