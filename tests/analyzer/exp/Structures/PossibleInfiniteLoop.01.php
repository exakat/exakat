<?php

$expected     = array('while (fgets($fp3) != \'a\') { /**/ } ',
                      'while (\'a\' != fgets($fp4)) { /**/ } ',
                      'while (1 != fgets($fp5)) { /**/ } ',
                      'while ($line = fgets($fp1) != \'a\') { /**/ } ',
                      'while (fgets($fp2) != \'a\') { /**/ } ',
                     );

$expected_not = array('while (0 != fgets($fp5)) { /**/ } ',
                     );

?>