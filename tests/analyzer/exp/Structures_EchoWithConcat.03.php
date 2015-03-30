<?php

$expected     = array('echo ( \'should\' . \'also\' . $be . \' with comma\')', 
                      'echo ( "should$be with comma")', 
                      'echo ( <<<HEREDOC
should\' too $be with comma
HEREDOC
)',
                      'echo ( "should" . "really $be with comma")',
                      'echo ( "$KO")');

$expected_not = array('echo ("OK")',
                      'echo ($OK)',
                      'echo (\'$KO\')',
                      'echo (<<<\'NOWDOC\'
nowdoc even with $var is fine.
NOWDOC
)');

?>