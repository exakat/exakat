<?php

class a {
    public    function apublicButSBPrivate(){}
    public    function apublicButReally(){}
    public    function apublicButReally2(){}
    public    function __construct(){}
    public    function __toString(){}
    public    function __clone(){}
    protected function aprotected(){}
    private   function aprivate(){}

    public    static function aspublicButReally(){}
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