<?php

function x() {
    global $x;
    global $$x;
    global ${y[2]};
    global $$f;
    global $$foo->bar;
    global ${$foo->bar};
}
?>