<?php

const A = "abc";

define ('B', 'abcd');

$b = "abc";
$c['abc'] = 'ab'.'c';

$b = "abcd";
$c['abcd'] = 'ab'.'cd';

class x {
    const C = <<<GREMLIN
abcde
GREMLIN;
}

$b = "abcde";
$c['abcde'] = 'ab'.'cde';

$x = <<<XX
abc{$c}abcd{$c}abcde{$d}abcdef
XX;
