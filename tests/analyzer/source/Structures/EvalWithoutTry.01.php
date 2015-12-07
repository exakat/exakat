<?php

eval ('$x = 1;');

try {
    eval ('$x = 2;');
} catch(Throwable $e) { }

$x->eval('$ev = 3');

Y::eval('$ev = 4');

?>