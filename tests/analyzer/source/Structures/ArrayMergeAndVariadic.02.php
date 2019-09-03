<?php

function foo() {
    array_merge($a);
    array_merge(...$b);
    array_merge($d, ...$c);
    array_merge(...$e, ...$f);

    if (isset($g[2])) { }
    array_merge(...$g);
    
    array_merge(...['a']);
}
?>