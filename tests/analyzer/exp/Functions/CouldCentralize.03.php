<?php

$expected     = array('str_replace(\'b\', \'a4\', $c)',
                      'str_replace(\'d\', \'a4\', $c)',
                      'str_replace(\'d\', \'a4\', $c)',
                      'str_replace(\'d\', \'a4\', $c)',
                      'die(\'b8.inc\')',
                      'die(\'b8.inc\')',
                      'die(\'b8.inc\')',
                      'die(\'b8.inc\')',
                      'die(\'b8.inc\')',
                      'die(\'b8.inc\')',
                      'die(\'b8.inc\')',
                      'die(\'b8.inc\')',
                     );

$expected_not = array('include(\'b.inc\')',
                     );

?>