<?php

$expected     = array('array_unique(range(1, 3))',
                      '\\array_unique(range(10, 30))',
                     );

$expected_not = array('array_unique(range(101,301))',
                     );

?>