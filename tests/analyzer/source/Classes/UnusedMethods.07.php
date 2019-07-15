<?php
class x implements ArrayAccess, i {
public function offsetExists ( mixed $offset ) : bool {}
public function offsetGet ( mixed $offset ) : mixed{}
public function offsetSet ( mixed $offset , mixed $value ) : void{}
public function offsetUnset ( mixed $offset ) : void{}
public function foo ( ) {}
public function bar () {}
}

interface i {
    function foo();
}


?>