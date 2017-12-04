<?php

$expected     = array('($f += $f)',
                      '($f += ($f + 3) % 4)',
                     );

$expected_not = array('($f + 3)',
                     );

?>