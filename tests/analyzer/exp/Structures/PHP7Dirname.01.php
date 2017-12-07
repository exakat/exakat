<?php

$expected     = array('dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname($x))))))))',
                      'dirname(dirname(dirname(dirname($x))))',
                      'dirname(dirname($x))',
                     );

$expected_not = array('dirname($x, 2)',
                      'dirname(dirname($y), 2)',
                      'dirname(dirname(dirname($x)))',
                     );

?>