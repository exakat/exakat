<?php

$expected     = array('echo "should" . "really $be with comma"',
                      'echo \'should\' . \'also\' . $be . \' with comma\'',
                      'echo "$KO"',
                      'echo <<<HEREDOC
should\' too $be with comma
HEREDOC',
                      'echo "should$be with comma"',
                      'echo "should", "really $be with", \'more comma\'',
                      'echo $this, "should", "really $be with", \'commas\'',
                     );

$expected_not = array('echo "OK"',
                      'echo $OK',
                      'echo \'$KO\'',
                      'echo <<<\'NOWDOC\'
nowdoc even with $var is fine.
NOWDOC',
                     );

?>