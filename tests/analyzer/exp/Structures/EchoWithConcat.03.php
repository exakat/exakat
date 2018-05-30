<?php

$expected     = array('echo (<<<HEREDOC
should\' too $be with comma
HEREDOC)',
                      'echo ("$KO")',
                      'echo ("should$be with comma")',
                      'echo ("should" . "really $be with comma")',
                      'echo (\'should\' . \'also\' . $be . \' with comma\')',
                     );

$expected_not = array('echo ( "OK" )',
                      'echo ( $OK )',
                      'echo ( \'$KO\' )',
                      'echo ( <<<\'NOWDOC\'
nowdoc even with $var is fine.
NOWDOC)',
                     );

?>