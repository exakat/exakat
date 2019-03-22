<?php

class a {
    function foo() {
        $this[1] = 3;
    }
}

interface i extends \arrayaccess {
     public function offsetExists (  $offset );
     public function offsetGet (  $offset );
     public function offsetSet (  $offset ,  $value );
     public function offsetUnset (  $offset );
}

class a1 implements i {
    function foo() {
        $this[2] = 3;
    }

     public  function offsetExists (  $offset ){}
     public  function offsetGet (  $offset ){}
     public  function offsetSet (  $offset ,  $value ){}
     public  function offsetUnset (  $offset ){}

}

interface i2 extends i { }

class a2 implements i2 {
    function foo() {
        $this[3] = 3;
    }

     public  function offsetExists (  $offset ){}
     public  function offsetGet (  $offset ){}
     public  function offsetSet (  $offset ,  $value ){}
     public  function offsetUnset (  $offset ){}

}

interface i3 extends i2 { }

class a2 implements i3 {
    function foo() {
        $this[4] = 3;
    }

     public  function offsetExists (  $offset ){}
     public  function offsetGet (  $offset ){}
     public  function offsetSet (  $offset ,  $value ){}
     public  function offsetUnset (  $offset ){}

}
?>