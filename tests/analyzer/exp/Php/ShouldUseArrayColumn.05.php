<?php

$expected     = array('for($i = 0 ; $i < count($n1) ; ++$i) { /**/ } ',
                      'for($j = 0 ; $j < count($n2) ; ++$j) { /**/ } ',

                     );

$expected_not = array('for($i = 0 ; $j < count($n3) ; ++$j) { /**/ } ',
                     );

?>