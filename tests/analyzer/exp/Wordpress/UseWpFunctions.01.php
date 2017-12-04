<?php

$expected     = array('mail(\'a\', \'b\', \'c\')',
                      'die(\'d\')',
                      'exit(\'e\')',
                      'header(\'f\')',
                     );

$expected_not = array('$a->rand( )',
                      'A::{\'b\' . \'c\'}( )',
                     );

?>