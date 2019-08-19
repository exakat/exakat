<?php

$expected     = array('substr($value, 0, 1) == "\\\\a"',
                      'substr($content, 0, I2) === S',
                      'substr($content, 0, I) === S2',
                     );

$expected_not = array('substr($value, 0, 1) == "\\a"',
                      'substr($value, 0, 1) == "\\a"',
                      'substr($value, 0, 1) == "\\t"',
                      'substr($content, 0, 2) == "\\0\\0"',
                      'substr($content, 0, 2) === "\\037\\213"',
                     );

?>