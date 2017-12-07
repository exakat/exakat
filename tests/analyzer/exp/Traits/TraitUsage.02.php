<?php

$expected     = array('use A\\B, A\\G',
                      'use UndefinedTrait',
                      'use A\\G',
                     );

$expected_not = array('use non\\_trait\\_use as b',
                      'use non\\_trait\\_use as c',
                     );

?>