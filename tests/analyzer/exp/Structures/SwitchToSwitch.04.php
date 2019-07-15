<?php

$expected     = array('if($a === 31) { /**/ } elseif($a === 32) { /**/ } elseif($a === 33) { /**/ } else { /**/ } ',
                      'if($a === 1) { /**/ } else  /**/  ',
                      'if($a === 11) { /**/ } elseif($a === 12) { /**/ } else  /**/  ',
                      'if($a === 21) { /**/ } else  /**/  ',
                     );

$expected_not = array('if($a === 111) { /**/ } else  /**/  ',
                     );

?>