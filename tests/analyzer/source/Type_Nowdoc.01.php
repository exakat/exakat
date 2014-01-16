<?php

$x = 1;

$heredoc = <<<HEREDOC
$x
HEREDOC;

$nowdoc = <<<'NOWDOC'
$x
NOWDOC;

print $heredoc;
print $nowdoc;

?>