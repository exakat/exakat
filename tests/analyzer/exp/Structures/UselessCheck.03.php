<?php

$expected     = array('if($c = sizeof($a->b4)) { /**/ } ',
                      'if(sizeof($a->b)) { /**/ } ',
                      'if(sizeof($a->b2) == 2) { /**/ } ',
                     );

$expected_not = array('if(!sizeof($a->b3)) { /**/ } ',
                     );

?>