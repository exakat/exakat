<?php

use a as b;

class a {
    public    $apublicButSBPrivate, $apublicButReally, $apublicButReally2;
    protected $aprotected;
    private   $aprivate;

    public    static $aspublicButSBPrivateSelf, $aspublicButSBPrivateStatic, $aspublicButSBPrivateFull, $aspublicButReally, $aspublicButReally2;
    protected static $asprotected;
    private   static $asprivate;
    
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

$b::$aspublicButReally2 = 4;
$b[1]::$aspublicButReally3 = 4;
$b->c::$aspublicButReally4 = 4;

?>