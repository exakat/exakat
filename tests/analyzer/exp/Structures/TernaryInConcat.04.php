<?php

$expected     = array('\'B\' . $b . \'C\' . $b ?? \'D\'',
                     );

$expected_not = array('\'B1\' . $b1 . \'C1\' . $b1 ?? \'D1\'',
                     );

?>