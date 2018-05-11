<?php

$expected     = array('substr($a, 0, 8) == \'\\r\\n\\t\\t\'',
                      'substr($a, 0, 4) == "\\r\\n"',
                     );

$expected_not = array('\'\\r\\n\\t\\t\'',
                      '"\\r\\n"',
                     );

?>