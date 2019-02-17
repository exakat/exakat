<?php

$expected     = array('define(\'a\' . $e, 3 + 4)',
                      'define(\'a\', $f + 3 + 4)',
                     );

$expected_not = array('define(\'a\', 3 + 4)',
                     );

?>