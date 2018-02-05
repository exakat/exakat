<?php

class x2 extends b {
    function __construct($a) {
        if (is_string($b)) {
            parent::__construct(3);
        } else {
            parent::__construct(4);
        }
    }

    // useless
    function other() {
        parent::__construct();
    }
}

class x3 extends b {
    function __construct($b) {
        if (is_string($b)) {
            parent::__construct(3);
        } else {
            ++$x;
            parent::__construct(4);
        }
    }

    // useless
    function other() {
        parent::__construct();
    }
}
?>