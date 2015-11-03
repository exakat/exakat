<?php

// an issue
list($a1, $b) = 'ad';
list($a2, $b) = "ad";
list($a3, $b) = "ad$c";
list($a4, $b) = <<<HEREDOC
ad$c
HEREDOC;

list($a5, $b) = 'a' . $b. "$d";

// ignore the left (methodreturn) and the right (method)
list($b) = $a->LIST($a, $b);

// simple assignement.
$c = 'ef';
list($g, $h) = $c;

var_dump($g);

?>