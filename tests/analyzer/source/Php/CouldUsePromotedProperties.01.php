<?php

class x {
    function __construct($a) {
        $this->b = $a;
    }
}

class x2 {
    function __construct($c) {
        $this->d = '3' + $c;
    }
}

class x3 {
    function __construct($c3) {
        $this->m($c3);
    }
    
    function m($c) {
        $this->d = $c;
    }
}

class x4 {
    function __construct($a4, $c4) {
        $a4->b = $c4;
    }
}

class x5 {
    function __construct($c5) {
        $this::$b5 = $c5;
    }
}


?>