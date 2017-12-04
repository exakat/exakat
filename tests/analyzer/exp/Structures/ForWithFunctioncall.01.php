<?php

$expected     = array('for($j = 0 ; $j < strlen($n) ; $j++) { /**/ } ',
                      'for($k = 0 ; $k < $nb ; $k = preg_match(\'\', $l)) { /**/ } ',
                     );

$expected_not = array('for($i = 0; $i < $nb; $i++) { /**/ } ',
                      'for(preg_match(\'\', $m); $m < $nb; $m++) { /**/ } ',
                     );

?>