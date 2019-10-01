<?php

$expected     = array('\'t$gfr ddde $rere}\'',
                      '\'t$fg3 \'',
                      '\'w$za \'',
                      '\'x$y\'',
                      '$inNowdoc that may be an error',
                      'Nowdoc wrongfully spread over 2 statement (part a)

NOWDOC;

$gg = <<<\'NOWDOC2\'
Nowdoc wrongfully spread over 2 statement (part b)',
                      '<<<HEREDOC2
Heredoc wrongfully spread over 2 statement (part a)

HEREDOC;

$ff_in = <<<HEREDOC2
Heredoc wrongfully spread over 2 statement (part b)

HEREDOC2',
                      '<<<\'NOWDOC2\'
Nowdoc wrongfully spread over 2 statement (part a)

NOWDOC;

$gg = <<<\'NOWDOC2\'
Nowdoc wrongfully spread over 2 statement (part b)

NOWDOC2',
                     );

$expected_not = array('\'twgfr ddde wrere$\'',
                     );

?>