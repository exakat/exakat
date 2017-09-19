<?php

use a as b;

class a {
    public    function apublicButSBProtected(){}
    public    function apublicButReally(){}
    public    function apublicButReally2(){}
    protected function aprotected(){}
    private   function aprivate(){}

    public    static function aspublicButSBProtectedSelf(){}
    public    static function aspublicButSBProtectedStatic(){}
    public    static function aspublicButSBProtectedFull(){}
    public    static function aspublicButReally(){}
    public    static function aspublicButReally2(){}
    protected static function asprotected(){}
    private   static function asprivate(){}
    
    function b() {
        $this->apublicButSBProtected() + $this->aprotected() + $this->aprivate();
        $a->apublicButReally();
        
        self::aspublicButSBProtectedSelf();
        static::aspublicButSBProtectedStatic();
        \a::aspublicButSBProtectedFull();
    }
}

$b->apublicButReally2();
$c->aprivate(); // Some other class

\a::aspublicButReally();
b::aspublicButReally2();
?>