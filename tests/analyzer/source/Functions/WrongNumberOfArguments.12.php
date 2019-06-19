<?php

class y {
    function __construct($a) {}
}

class x extends y {
    function __construct($a) {}

    function foo() {
        new parent();
        new parent(1);
        new parent(1,2 );

        new self();
        new self(1);
        new self(1,2 );
    }
}

?>