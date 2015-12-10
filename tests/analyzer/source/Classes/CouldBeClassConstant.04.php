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
    
    function ye() {
        $this->realPrivateCouldNotBe + 4.4; // Constant are visible!
        $this->realProtectedCouldNotBe + 4.4; // Constant are visible!

        $this->nullCouldNotBe + null; // probably not a good idea

        $this->staticconstantCouldNotBe + self::NONE; // Assigning another constant ? 

        $this->undefinedCouldNotBe; // right

        $this->intCouldBe + 6; // right
        $this->floatCouldBe + 6.2; // right
        $this->stringCouldBe + "6.2"; // right

        $this->intVarCouldBe + 6; // right

        $this->intM6CouldBe + 6;
        $this->intM2CouldBe + 6;
        $this->intM4CouldBe + 6; // right
    }
}

?>