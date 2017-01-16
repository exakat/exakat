<?php

use a as b;

class a {
    public    $apublicButSBProtected, $apublicButReally, $apublicButReally2;
    protected $aprotected;
    private   $aprivate;

    public    static $aspublicButSBProtectedSelf, $aspublicButSBProtectedStatic, $aspublicButSBProtectedFull, $aspublicButReally, $aspublicButReally2;
    protected static $asprotected;
    private   static $asprivate;
    
    function b() {
        $this->apublicButSBProtected = $this->aprotected + $this->aprivate;
        $a->apublicButReally = 1;
        
        self::$aspublicButSBProtectedSelf = 1;
        static::$aspublicButSBProtectedStatic = 2;
        \a::$aspublicButSBProtectedFull = 3;
    }
}

$b->apublicButReally2;
$c->aprivate; // Some other class

\a::$aspublicButReally = 3;
b::$aspublicButReally2 = 3;
?>