<?php

namespace a\b\c;

class x {
    function __construct() {
        echo __METHOD__.PHP_EOL;
    }
}

$string = 'a\b\c\x';
$string = '\a\b\c\x';

$string = 'a\B\c\x';
$string = '\a\B\c\x';

$string = 'a\b\c\X';
$string = '\a\b\c\X';

$string = 'a\b\C\x';
$string = '\a\b\C\x';

$string = 'A\b\C\x';
$string = '\A\b\C\x';

new $string;
