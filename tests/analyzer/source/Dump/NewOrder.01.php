<?php

class a {
    function __construct() {
        $this->a = new b;
    }
}

class b {
    function __construct() {
        $this->c = new c;
        $this->c2 = new c2;
    }
}

class c {
    function __construct() {}
}

class c2 {
    function __construct() {}
}

?>