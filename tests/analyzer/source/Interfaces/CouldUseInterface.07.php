<?php
class a5 {
public function offsetExists (  $offset ){}
public function offsetGet (  $offset ){}
public function offsetSet (  $offset ,  $value ){}
public function offsetUnset (  $offset ){}
}

class a6 {
public function offsetexists (  $offset ){}
public function offsetget (  $offset ){}
public function offsetset (  $offset ,  $value ){}
public function offsetunset (  $offset ){}
}

interface i {
    function i();
}

class a7 implements i {
    function i(){}
}

class a8  {
    function i(){}
}

class a9  {
    function I(){}
}

interface j {
    function i($b);
    function j();
}

class a10  {
    function I($a){}
    function J(){}
}

interface k {
    function i();
    function j();
}

class a11  {
    function I(){}
    function J(){}
}

?>