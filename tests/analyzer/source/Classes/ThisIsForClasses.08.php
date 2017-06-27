<?php

foreach($a as $this) {}

function foo() {
    foreach($a as $this) {}
}

class c {
    function foo() {
        foreach($a as $this) {}
    }
}


trait t {
    function foo() {
        foreach($a as $this) {}
    }

    function foo2() {
        foreach($this as $a) {}
    }
}

?>