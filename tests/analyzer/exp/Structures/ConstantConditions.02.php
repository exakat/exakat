<?php

$expected     = array('while (true) { /**/ } ',
                      'while (3 << 5) { /**/ } ',
                     );

$expected_not = array('while ($z++) { /**/ } ',
                      'while ($x) { /**/ } ',
                     );

?>