<?php

namespace a\b\c;

use X as B;

class X {}

$a = '\X';
$b = '\x';
$c = '\a';
$d = 'x';
$d = '\a\b\c\x';
$d = 'a\b\c\x';
$e = 'b';       // 
$d = "x$a";

?>