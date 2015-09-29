<?php

function x() {
    global $x, $$x, ${y[2]}, $$f, $$foo->bar, ${$foo->bar};
    global $x, $bx, $cx, $dx;
}
?>