<?php

// the good one
class x extends b {
    function __construct($a) {
        parent::__construct(1);
    }

    // useless
    function other() {
        parent::__construct();
    }
}

// the bad one
class x4 extends b {
    function __construct($d) {
        ++$s;
        parent::__construct(1);
    }

    // useless
    function other() {
        parent::__construct();
    }
}

class x2 extends b {
    function __construct($b) {
        if (rand()) {
            parent::__construct(3);
        }
    }

    // useless
    function other() {
        parent::__construct();
    }
}

// Not in construct
class x3 {
    function boo($c) {
        if (rand()) {
            parent::__construct(5);
            parent::__construct(6); 
        }
    }
}

// Not in construct
class x4 {
    function __construct($c) {
        // Not the constructor
        parent::init();
    }
}

?>