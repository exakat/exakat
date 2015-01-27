<?php

f(1, 'a', <<<HEREDOC
a
HEREDOC
, <<<'NOWDOC'
BadFunctionCallException
NOWDOC
, 5);

?>