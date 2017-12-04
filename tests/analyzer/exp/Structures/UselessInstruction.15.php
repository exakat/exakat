<?php

$expected     = array('\'g2\' . (\'\')',
                      '\'g\' . \'\'',
                     );

$expected_not = array('\'e\' . ($a == \'b\' ? $c : \'\')',
                     );

?>