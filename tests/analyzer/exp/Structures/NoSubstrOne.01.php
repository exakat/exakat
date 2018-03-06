<?php

$expected     = array('substr($b, 0, 1)',
                      'substr($b, 0, +1)',
                      'substr($b, 0, "1")',
                      'substr($b, 0, true)',
                     );

$expected_not = array('substr($b, 0, -1)',
                      'mb_substr($mb_, 0, 1)',
                     );

?>