<?php

//global $this;
//static $this;
// can't test on PHP 7.0

function foo() {
    global $this;
//    static $this;
// can't test on PHP 7.0
}


trait t {
    function foo() {
        global $this;
//        static $this;
// can't test on PHP 7.0
    }
}

?>