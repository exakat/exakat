<?php

$expected     = array('\\A\\B\\C',
                      '-(4 + 3)',
                      '"String"',
                      'MY_CONSTANT',
                      '<<<HEREDOC

silly string

HEREDOC',
                     );

$expected_not = array('\'b\'',
                     );

?>