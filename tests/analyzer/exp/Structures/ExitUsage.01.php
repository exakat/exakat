<?php

$expected     = array('die(\'e\')',
                      'die( )',
                      'die ',
                      'exit(\'f\')',
                      'exit( )',
                      'exit ',
                     );

$expected_not = array('exit(3)',
                     );

?>