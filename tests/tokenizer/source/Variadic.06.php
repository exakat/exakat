<?php

a(...$a->b->c->d()->e ?? array());
a(...$a2::$b::$c::d()::e ?? array());
a(...$a3[1][3][4] ?? array());

$b = &$a ?? 1;
?>