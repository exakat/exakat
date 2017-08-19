<?php

$expected     = array('for($index = 0 ; $index < sizeof($lines) ; $index++) { /**/ } ',
                      'for($index6 = 0 ; $index < sizeof($lines) ; ++$index6) { /**/ } ',
                      );

$expected_not = array('for ($index5 = 0; $index < sizeof($lines); $index5++) { /**/ } ',
                      'for ($index4 = 0; $index < sizeof($lines); $index++) { /**/ } ',
                      'for ($index2 = 1, $index3 = 0; $index < sizeof($lines); $index++) { /**/ } ',
                      'for ($index1 = 1; $index < sizeof($lines); $index++) { /**/ } ',
                      );

?>