<?php

$expected     = array('do { /**/ } while($line = fgets($fp1) != \'a\')',
                      'do { /**/ } while(fgets($fp2) != \'a\')',
                      'do { /**/ } while(fgets($fp3) != \'a\')',
                      'do { /**/ } while(\'a\' != fgets($fp4))',
                      'do { /**/ } while(1 != fgets($fp5))',
                     );

$expected_not = array('do { ++$b; } while(0 != fgets($fp6))',
                     );

?>