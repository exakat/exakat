<?php

$expected     = array("'t\$gfr ddde \$rere}'",
                      "'t\$fg3 '",
                      "'w\$za '",
                      "'x\$y'",
                      "Nowdoc wrongfully spread over 2 statement (part a)

NOWDOC;

\$gg = <<<'NOWDOC2'
Nowdoc wrongfully spread over 2 statement (part b)

",
                        "\$inNowdoc that may be an error

",
                        "Nowdoc wrongfully spread over 2 statement (part a)

NOWDOC;

\$gg = <<<'NOWDOC2'
Nowdoc wrongfully spread over 2 statement (part b)

",
                        "Heredoc wrongfully spread over 2 statement (part a)

HEREDOC;

\$ff_in = <<<HEREDOC2
Heredoc wrongfully spread over 2 statement (part b)

");

$expected_not = array();

?>