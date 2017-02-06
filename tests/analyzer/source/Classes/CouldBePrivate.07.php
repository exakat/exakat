<?php

use a as b;

class a {
    public    $apublicButSBPrivate = 2, $apublicButReally = 3 , $apublicButReally2 = 4;
    protected $aprotected = 5;
    private   $aprivate = 6;

    public    static $aspublicButSBPrivateSelf = 7 , $aspublicButSBPrivateStatic = 8, $aspublicButSBPrivateFull = 9, $aspublicButReally = 10, $aspublicButReally2 = 11;
    protected static $asprotected = 12;
    private   static $asprivate = 13;
    
    function b() {
        $this->apublicButSBPrivate = $this->aprotected + $this->aprivate;
        $a->apublicButReally = 1;
        
        self::$aspublicButSBPrivateSelf = 1;
        static::$aspublicButSBPrivateStatic = 2;
        \a::$aspublicButSBPrivateFull = 3;
    }
}

$b->apublicButReally2;
$c->aprivate; // Some other class
\a::$aspublicButReally = 3;
b::$aspublicButReally2 = 3;
?>