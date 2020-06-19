<?php

$expected     = array('shell_exec($a)',
                      'shell_exec($c)', 
                      'shell_exec($e)', 
                     );

$expected_not = array('$y->foo2(1, $z)',
                      'x::foo3(4, $z)',
                     );

?>