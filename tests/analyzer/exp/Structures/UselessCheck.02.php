<?php

$expected     = array('if(count(B::$a) > 0)  /**/  ',
                      'if(!empty($a)) { /**/ } ',
                     );

$expected_not = array('if (sizeof($a->b) != 0) { /**/ } ',
                      'if (sizeof($a->b) != 0) { /**/ } ',
                      'if (count($a[3]) > 0) { /**/ } ',
                     );

?>