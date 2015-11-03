<?php

$a = 'x$y';
$b = 'w$za ';
$c = 't$fg3 ';
$d = "t$fg3 $f ";
$e = 't$gfr ddde $rere}';
$ee = 'twgfr ddde wrere}';
$eef = 'twgfr$ ddde wrere}';
$eeg = 'twgfr ddde wrere$';
$eeh = 'twgfr ddde wrere\$h';

$f = <<<HEREDOC
$inHeredoc something is brewing

HEREDOC;

$ff = <<<HEREDOC
NoVarInHeredoc is brewing

HEREDOC;

$g = <<<'NOWDOC'
$inNowdoc that may be an error

NOWDOC;

$gg = <<<'NOWDOC'
inNowdoc all is fine

NOWDOC;


// wrong nowdoc2
$g = <<<'NOWDOC2'
Nowdoc wrongfully spread over 2 statement (part a)

NOWDOC;

$gg = <<<'NOWDOC2'
Nowdoc wrongfully spread over 2 statement (part b)

NOWDOC2;

// end wrong nowdoc2

// wrong heredoc
$ff = <<<HEREDOC2
Heredoc wrongfully spread over 2 statement (part a)

HEREDOC;

$ff_in = <<<HEREDOC2
Heredoc wrongfully spread over 2 statement (part b)

HEREDOC2;

// wong heredoc end

?>