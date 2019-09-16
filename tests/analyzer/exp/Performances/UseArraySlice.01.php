<?php

$expected     = array('foreach($a as $b) { /**/ } ',
                      'do /**/ while(!empty($a))',
                     );

$expected_not = array('while(!empty($a)) { /**/ } ',
                     );

?>