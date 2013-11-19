<?php

$y = 'a';
class C {
    public static $y = 'f';
    
    function a() { return 'b'; }
    
}

$x = new C::$y();
?>