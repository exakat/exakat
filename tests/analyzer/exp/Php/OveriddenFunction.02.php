<?php

$expected     = array('dirname(\'/a/b/c\')',
                      'split(\'a\', \'b\')',
                     );

$expected_not = array('\\dirname(\'/a/b/c\')',
                     );

?>