<?php

class x1 {
    function foo1() {}
    function foo4() {}
    function foo7() {}
}

class x21 extends x1 {
    function foo4() {}
    function foo7() {}
}

class x22 extends x1 {
    function foo4() {}
    function foo7() {}
}

class x23 extends x1 {
    function foo4() {}
    function foo7() {}
}

class x31 extends x21 {
    function foo7() {}
}

class x32 extends x21 {
    function foo7() {}
}

class x33 extends x21 {
    function foo7() {}
}
?>