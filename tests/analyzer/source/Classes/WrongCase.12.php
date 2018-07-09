<?php

class a {}

class x extends a {
    function foo() {
        new self();
        new parent();
        new static();
        new X();
    }
}

?>