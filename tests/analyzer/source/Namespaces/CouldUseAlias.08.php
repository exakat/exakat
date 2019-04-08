<?php

use function A\foo;
use const A\C as C;

A\foo();
B\A\foo();
a\FOO();
\A\foo();

echo A\C;
echo \A\C;
echo C;

new \A\C(2);

?>