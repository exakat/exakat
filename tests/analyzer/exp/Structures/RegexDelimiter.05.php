<?php

$expected     = array('preg_match(\'/ abc\' . \' cde \' . \' dfg /\', $a)',
                     );

$expected_not = array('preg_replace(\'$a$\', \'b\', $a)',
                     );

?>