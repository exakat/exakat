<?php

// Really invalid
preg_replace('/[^\w-.]/', '', null);


// OK
preg_replace_callback("/\\\$(\w+)\\\$?/", function ($matches) { /**/ } , null);

const A = '(([^\*\:\/\\\\\?\[\]\\\'])+|(\\\'\\\')+)+';
preg_match("/^'" . A . '(\\:' . A . ")?'\\!\\$?([A-Ia-i]?[A-Za-z])?\\$?\\d+:\\$?([A-Ia-i]?[A-Za-z])?\\$?\\d+$/u", null);

$a = 1;
preg_match("/{$a}[ -]" . ($a ? str_replace('.', '\\.', $a) . ' ' : '') . "/m", null);

preg_match("/\s/m", null);
preg_match("/\\s/m", null);

preg_match('/[\\xC0-\\xFF]/',  null);

const C = 'd';
preg_match('/[ab'.(C).']/',  null);
?>