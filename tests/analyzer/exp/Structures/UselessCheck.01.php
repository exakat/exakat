<?php

$expected     = array('if(count($a) > 0) { /**/ } ',
                      'if(sizeof($a->b) > 0) { /**/ } ',
                     );

$expected_not = array('if (sizeof($a->b1) > 0) { /**/ } ',
                      'if (count($ad) > 0) { /**/ } ',
                     );

?>