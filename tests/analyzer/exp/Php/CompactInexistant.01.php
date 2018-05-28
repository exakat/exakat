<?php

$expected     = array('compact(\'c2\')',
                      'compact(\'c\'),',
                      'compact(\'c3\')',
                     );

$expected_not = array('compact(\'a3\', \'b3\', \'d3\')',
                      'compact(\'a\', \'b\')',
                      'compact(\'a2\', \'b2\')',
                     );

?>