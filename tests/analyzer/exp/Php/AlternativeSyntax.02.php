<?php

$expected     = array('if($a) :   /**/  elseif($b) :   /**/  elseif($c) :   /**/  else :  /**/   endif',
                      'elseif($b) :   /**/  elseif($c) :   /**/  else :  /**/  ',
                      'elseif($c) :   /**/  else :  /**/  ',
                     );

$expected_not = array('if ($a) { /**/ } elseif ($b) { /**/ } elseif ($c) { /**/ } else { /**/ } ',
                     );

?>