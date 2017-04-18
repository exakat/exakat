<?php

$expected     = array('($a2[$i] != \';\') && ($tokens[$i] != \'{\') && ($i - $id < 20)', 
                      '($tokens[$i] != \';\') && ($a0[$i] != \'{\') && ($i - $id < 20)', 
                      '($a1[$i] != \';\') && ($tokens[$i] != \'{\') && ($i - $id < 20)');

$expected_not = array('$a && $b');

?>