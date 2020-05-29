<?php

class x1 {
    function foo() {
        $this->$p;
    }
}

class x2 {
    function foo() {
        $this->$m();
    }
}

class x3 {
    function foo() {
        $this::$m();
    }
}

class x4 {
    function foo() {
        $this->p;
    }
}

class x5 {
    function foo() {
        $this->m();
    }
}

class x6 {
    function foo() {
        $this::m();
    }
}

class x7 {
    function foo() {
        $this::$p;
    }
}

class x8 {
    function foo() {
        $this->{$c[8]};
    }
}

class x9 {
    function foo() {
        $this->{$c[9]}();
    }
}

class x10 {
    function foo() {
        $this::{$c[10]}();
    }
}

class x11 {
    function foo() {
        self::{$c[11]}();
    }
}

?>