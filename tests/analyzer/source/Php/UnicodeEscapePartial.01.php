<?php

// OK, just for check
$u = "\U{";

// OK for PHP 5, error for PHP 7
$u = "\u{";
$u = '\u{';
$u = <<<UES
\a\b\u{
UES
;

$u = <<<'UES'
\u{ab$c
UES
;

// OK for PHP 5 and PHP 7, but with different meaning
echo "\u{aa}";
echo "\u{0000aa}";
echo "\u{9999}";