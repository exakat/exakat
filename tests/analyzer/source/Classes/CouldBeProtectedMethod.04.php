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
        $this->aPUBLICButSBProtected() + $this->aprotected() + $this->aprivate();
        $a->apublicBUTReally();
        
        self::ASpublicButSBProtectedSelf();
        static::aspublicButSBProtectedSTATIC();
        \a::aspublicButSBProtectedFULL();
    }
}

$b->apublicButREALLY2();
$c->aprivatE(); // Some other class

\a::aspublicButRealLY();
b::aspublicButREALly2();
?>