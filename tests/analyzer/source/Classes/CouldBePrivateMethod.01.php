<?php

use a as b;

class a {
    public    function apublicButSBPrivate(){}
    public    function apublicButReally(){}
    public    function apublicButReally2(){}
    protected function aprotected(){}
    private   function aprivate(){}

    public    static function aspublicButSBPrivateSelf(){}
    public    static function aspublicButSBPrivateStatic(){}
    public    static function aspublicButSBPrivateFull(){}
    public    static function aspublicButReally(){}
    public    static function aspublicButReally2(){}
    protected static function asprotected(){}
    private   static function asprivate(){}
    
    function b() {
        $this->apublicButSBPrivate();
        $this->aprotected() + $this->aprivate();
        $a->apublicButReally(1);
        
        self::aspublicButSBPrivateSelf();
        static::aspublicButSBPrivateStatic();
        \a::aspublicButSBPrivateFull();
    }
}


$b->apublicButReally2();
$c->aprivate(); // Some other class
\a::aspublicButReally();
b::aspublicButReally2();
?>