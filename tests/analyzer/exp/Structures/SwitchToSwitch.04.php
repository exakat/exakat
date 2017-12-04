<?php

$expected     = array('if(31) { /**/ } elseif(32) { /**/ } elseif(33) { /**/ } else { /**/ } ',
                      'if(1) { /**/ } else  /**/  ',
                      'if(11) { /**/ } elseif(12) { /**/ } else  /**/  ',
                      'if(21) { /**/ } else  /**/  ',
                     );

$expected_not = array('if(111) { /**/ } else  /**/  ',
                     );

?>