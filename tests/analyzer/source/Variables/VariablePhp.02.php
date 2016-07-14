<?php

$argv = "{$_POST[$_GET[ENV]]} $PHP_SELF $GLOBALS[x] $globals";

$argc = <<<HEREDOC
$_REQUEST[variable] $COOKIE
HEREDOC;

?>