<?php

$expected     = array('for($i = 0 ; count($b) ; ++$i) { /**/ } ',
                     );

$expected_not = array('for($i = 0 ; isset($b[$i]) ; ++$i) { /**/ } ',
                      'for($i = 0 ; $b < $a ; ++$i) { /**/ } ',
                     );

?>