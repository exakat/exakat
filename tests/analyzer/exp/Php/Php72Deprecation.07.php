<?php

$expected     = array('assert(\'assertion\', \'F\' . $g)',
                     );

$expected_not = array('assert(!empty($a), "b\\n")',
                      'assert($d === g($d), \'d\' . $f)',
                     );

?>