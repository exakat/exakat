<?php

$expected     = array('array_pop(explode(\'.\', $path))',
                      'array_pop(split(\'.\', $path))',
                      'substr(strrchr($path, "."), 1)',
                     );

$expected_not = array('array_shift(split(\'.\', $path))',
                      'substr(strrchr($path, "."), 2)',
                      'substr(strrchr($path, ".."), 2)',
                     );

?>