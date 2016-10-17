<?php

//foreach($a as $this => $b) {}

function foo() {
//    foreach($a as $this => $b) {}
}

class c {
    function foo() {
//        foreach($a as $this => $b) {}
    }
}


trait t {
    function foo() {
//        foreach($a as $b => $this) {}
    }
}

?>