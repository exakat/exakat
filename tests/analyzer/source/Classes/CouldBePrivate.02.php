<?php

use a as c;

class d {
    function ad() {
        $this->aprotectedButSBPrivate2 = 3;
        $a->aprotectedButSBPrivate4 = 3;
    }
}

class a {
    public    $apublic;
    protected $aprotectedButSBPrivate, $aprotectedButReally, $aprotectedButSBPrivate2, $aprotectedButSBPrivate3, $aprotectedButSBPrivate4;
    private   $aprivate;

    public    static $aspublic;
    protected static $asprotectedButSBPrivateSelf, $asprotectedButSBPrivateStatic, $asprotectedButSBPrivateFull, $asprotectedButSBPrivateChildren;
    protected static $asprotectedReallySelf, $asprotectedReallyStatic, $asprotectedReallyFull, $asprotectedReallyChildren;
    private   static $asprivate;

    function aa() {
        $this->aprotectedButSBPrivate = $this->apublic + $this->aprivate;
        $a->aprotectedButSBPrivate3 = 1;
        $this->aprotectedButSBPrivate2 = $a->aprotectedButSBPrivate4;
        
        self::$asprotectedButSBPrivateSelf = 1;
        static::$asprotectedButSBPrivateStatic = 2;
        \a::$asprotectedButSBPrivateFull = 3;
        \a::$asprotectedButSBPrivateChildren = 3;

        self::$asprotectedReallySelf = 1;
        static::$asprotectedReallyStatic = 2;
        \a::$asprotectedReallyFull = 3;
        \a::$asprotectedReallyChildren = 3;
    }

}

class b extends a {    
    function ab() {
        $this->aprotectedButReally = $this->apublic + $this->aprivate;
        $a->aprotectedButReally = 1;
        
        self::$asprotectedButSBPrivateSelf = 1;
        static::$asprotectedButSBPrivateStatic = 2;
        \a::$asprotectedButSBPrivateFull = 3;
        \b::$asprotectedButSBPrivateChildren = 3;
    }
}

?>