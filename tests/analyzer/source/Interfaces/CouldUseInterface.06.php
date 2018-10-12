<?php

interface i {
    function i();
}

class a0             implements sessionidinterface {
    function create_sid() {}
}

class a1              {
    function create_sid() {}
}

class a2             implements i {
    function i() {}
}

class a3             implements sessionidinterface, i {
    function create_sid() {}
    function i() {}
}

class a4              {
    function create_sid() {}
    function i() {}
}

class a5 {
    public function offsetExists (  $offset ){}
    public function offsetGet (  $offset ){}
    public function offsetSet (  $offset ,  $value ){}
    public function offsetUnset (  $offset ){}
}

?>