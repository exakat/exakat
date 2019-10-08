<?php

$expected     = array('mb_substr(\'ABC\', 1, \'UTF8\')',
                     );

$expected_not = array('mb_substr(\'ABC\', 1, 2, \'UTF8\')',
                     );

?>