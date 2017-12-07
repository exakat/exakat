<?php

$expected     = array('if(31)  /**/  elseif(32)  /**/  elseif(33)  /**/  elseif(34)  /**/  else  /**/  ',
                      'if(1)  /**/  elseif(2)  /**/  elseif(3)  /**/  else  /**/  ',
                      'if(11)  /**/  elseif(12)  /**/  elseif(13)  /**/  ',
                     );

$expected_not = array('if(1) { /**/ } elseif(2) { /**/ } else { /**/ } ',
                     );

?>