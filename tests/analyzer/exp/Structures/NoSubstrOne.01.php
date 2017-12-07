<?php

$expected     = array('substr($b, 0, -1)',
                      'substr($b, 0, 1)',
                     );

$expected_not = array('mb_substr($mb_, 0, 1)',
                     );

?>