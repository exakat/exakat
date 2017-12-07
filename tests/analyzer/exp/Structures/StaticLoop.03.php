<?php

$expected     = array('for($a = 0 ; $a < 10 ; ++$a) { /**/ } ',
                      'for($a2 = 0 ; $a2 < 10 ; ++$a2) /**/ ',
                      'for($a3 = 0 ; $a3 < 10 ; ++$a3) { /**/ } ',
                     );

$expected_not = array('for($a4 = 0 ; $a4 < 10 ; ++$a4) { /**/ } ',
                     );

?>