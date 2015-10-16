<?php 

class x {
    static public $staticProperty1, $staticProperty2, $staticProperty3;
    private $noStaticPrivateProperty1, $noStaticPrivateProperty2, $noStaticPrivateProperty3;
    public $noStaticPublicProperty1, $noStaticPublicProperty2, $noStaticPublicProperty3;
    protected $noStaticProtectedProperty1, $noStaticProtectedProperty2, $noStaticProtectedProperty3;
    public static $PropertyStatic1, $PropertyStatic2, $PropertyStatic3;
    
    function y() {
        static $z1, $z2, $z4, $z3 = 3;
    }
}

?>