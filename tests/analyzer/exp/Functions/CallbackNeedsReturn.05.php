<?php

$expected     = array('array_map("cube3", $a)',
                     );

$expected_not = array('array_map("cube2", $a)',
                      'array_map("cube", $a)',
                     );

?>