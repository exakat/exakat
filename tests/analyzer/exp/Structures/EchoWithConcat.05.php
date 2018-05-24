<?php

$expected     = array('echo <<<HEREDOC
Yes
$with variable
HEREDOC
', 
                     );

$expected_not = array('echo <<<\'NOWDOC\'
Yes
$with fake variable
NOWDOC',
                      'echo <<<HEREDOC
Yes
without variable
HEREDOC
',
                     );

?>