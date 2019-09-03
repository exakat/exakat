<?php

function foo() {
    array_merge($a);
    array_merge(...$b);
    array_merge($d, ...$c);
    array_merge(...$e, ...$f);

    if (empty($g)) { }
    array_merge(...$g);
    
    array_merge(...['a']);
}
?>