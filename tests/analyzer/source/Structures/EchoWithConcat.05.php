<?php

echo <<<'NOWDOC'
Yes
$with fake variable
NOWDOC;

echo <<<HEREDOC
Yes
$with variable
HEREDOC;

echo <<<HEREDOC
Yes
without variable
HEREDOC;

?>