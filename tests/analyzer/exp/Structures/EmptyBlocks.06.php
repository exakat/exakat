<?php

$expected     = array('if($a11)  /**/  elseif($a21)  /**/  else  /**/  ',
                      'if($a15) :   /**/  elseif($a25) :   /**/  else :  /**/   endif',
                      'if($a16) :   /**/  elseif($a26) :   /**/  else :  /**/   endif',
                      'if($a12) { /**/ } elseif($a22) { /**/ } else { /**/ } ',
                      'if($a13) { /**/ } elseif($a23) { /**/ } else { /**/ } ',
                      'if($a14) :   /**/  elseif($a24) :   /**/  else :  /**/   endif',
                     );

$expected_not = array('if($a18) :   /**/  elseif($a2) :   /**/  else :  /**/   endif',
                      'if($a17)  /**/  elseif($a2)  /**/  else  /**/  ',
                     );

?>