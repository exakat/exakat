<?php

class x {
    private $realPrivateCouldNotBe = 3.3; // Constant are visible!
    protected $realProtectedCouldNotBe = 3.3; // Constant are visible!
    
    public $nullCouldNotBe = null; // probably not a good idea
    
    public $staticconstantCouldNotBe = self::NONE; // Assigning another constant ? 

    public $undefinedCouldNotBe; // right

    public $intCouldBe = 1; // right
    public $floatCouldBe = 1.2; // right
    public $stringCouldBe = "1.2"; // right

    var    $intVarCouldBe = 1; // right

    public $intM1CouldBe = 1, $intM2CouldBe = 1, $intM3CouldBe = 1; // right
    
}

?>