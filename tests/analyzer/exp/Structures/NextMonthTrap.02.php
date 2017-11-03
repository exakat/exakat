<?php

$expected     = array('strtotime("$d month")',
                     );

$expected_not = array('strtotime($a)',
                      'echo strtotime($b->c)',
                     );

?>