<?php

foreach($a as $b => $this) {}

function foo() {
    foreach($a as $b => $this) {}
}

class c {
    function foo() {
        foreach($a as $b => $this) {}
    }
}


trait t {
    function foo() {
        foreach($a as $b => $this) {}
    }
}

    function foo() {
        foreach($a as $b => $this) {}
    }

?>