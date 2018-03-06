<?php 

class x {
    static public $staticProperty;
    private $noStaticPrivateProperty;
    public $noStaticPublicProperty;
    protected $noStaticProtectedProperty;
    
    function foo() {
        static $staticVariable;
    }
}

?>