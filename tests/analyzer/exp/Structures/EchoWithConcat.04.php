<?php

$expected     = array('print "should" . "really $be with comma"',
                      'print \'should\' . \'also\' . $be . \' with comma\'',
                      'print "$KO"',
                      'print <<<HEREDOC
should\' too $be with comma
HEREDOC',
                      'print "should$be with comma"',
                      'print <<<HEREDOC
should", $be, " all", "ok"
HEREDOC',
                     );

$expected_not = array('print "OK"',
                      'print $OK',
                      'print \'$KO\'',
                      'print <<<\'NOWDOC\'
nowdoc even with $var is fine.
NOWDOC',
                     );

?>