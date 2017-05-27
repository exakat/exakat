<?php
class x {
    static $staticClass;
    public $x = 1, $b;
    public $x2, $b2 = 2;
    private $private1, $private2 = 2, $private3;
    protected $protected1, $protected2 = 2, $protected3;
    
    public function foo() {
        global $global;
        static $staticFunction;
        
    }
}
?>