<?php

function x() {
    global $$foo->bar;
    
    global ${$foo['bar']['baz']};
}

function x2() {
    global $$foo2->bar2, $foo2, ${$foo4['bar5']['baz6']};
}

?>