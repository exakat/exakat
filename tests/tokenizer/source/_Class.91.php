<?php
class ab {
    function __construct($a) { }
}

class b extends ab {
    function __construct($a) { }
}

class bb extends b {

}

class bbb extends bb {

}

class A extends bbb {
    function foo() {
        new static;
        new self;
        new parent;

        new static();
        new self();
        new parent();
    }
}

class C extends b {
    function foo() {
        new static;
        new self;
        new parent;

        new static();
        new self();
        new parent();
    }
}
