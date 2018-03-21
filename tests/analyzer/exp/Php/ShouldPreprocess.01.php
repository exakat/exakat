<?php

$expected     = array('chr(1)',
                      'chr(\'a\')',
                      'chr(1.1)',
                      'chr("\120")',
                      'chr(32)',
                     );

$expected_not = array('chr([3,2,])',
                     );

?>