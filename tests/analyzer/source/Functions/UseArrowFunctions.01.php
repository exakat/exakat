<?php

array_map(fn(A $b): int => $b->c, $array);
array_map(fn(A $b) => $b->c, $array);
$e->fn(4);

?>