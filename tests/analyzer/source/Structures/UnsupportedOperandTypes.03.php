<?php

class b { 
    public array $p = array(); 
    public static array $sp = array(); 
}

$o = new b;
1 + $o->p; 
1 + b::$sp; 

array() + $o->p; 
array() + b::$sp; 

?>