<?php

class c {
    function foo(self $a) {}
}

interface i {
    function foo(self $a);
}

trait t {
    function foo(self $a) {}
}

?>