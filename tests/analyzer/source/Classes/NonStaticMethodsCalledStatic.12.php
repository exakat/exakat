<?php

class a  {
    function foo() { echo __METHOD__;}
}

class a2  {
    function foo() { echo __METHOD__;}
}

class b extends a{
    function bar() { 
        a::foo();
        c::foo();
        a2::foo();
        b::foo();
    }

    function foo() { echo __METHOD__;}

}

class c extends b {
    function foo() { echo __METHOD__;}
}

(new a)->bar();

?>