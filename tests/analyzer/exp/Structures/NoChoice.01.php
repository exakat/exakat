<?php

$expected     = array('$a == 3 ? $a->method( ) : $a->method( )',
                      '$a == 2 ? $b[2] : $b[2]',
                      '$a == 1 ? $b : $b',
                      'if($a == 1)  /**/  else  /**/  ',
                      'if($a == 4) { /**/ } else { /**/ } ',
                      'if($c == 2) { /**/ } else { /**/ } ',
                      'if($a == 2)  /**/  else  /**/  ',
                      'if($a == 3) :   /**/  else :  /**/   endif',
                     );

$expected_not = array('$c == 1 ? $b[1] : $b[3]',
                      'if($c == 1) echo $b[1]; else echo $b[3]',
                     );

?>