<?php

$expected     = array('($a4[$i] != \';\') && ($tokens[$i] != \'{\')',
                     );

$expected_not = array('($a2[$i] != \';\') && ($tokens[$i] != \'{\') && ($i - $id < 20)',
                      '($tokens[$i] != \';\') && ($a0[$i] != \'{\') && ($i - $id < 20)',
                      '($a1[$i] != \';\') && ($tokens[$i] != \'{\') && ($i - $id < 20)',
                      '$a && $b',
                     );

?>