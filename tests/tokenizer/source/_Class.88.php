<?php


class x {}

class y extends x {
    function foo() {
        new parent;
        new parent();
    }
}
?>