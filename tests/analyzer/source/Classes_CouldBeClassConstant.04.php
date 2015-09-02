<?php

class x {
    private $realCouldBe = 3.3; // Constant are visible!
    
    public $nullCouldNotBe = null; // probably not a good idea
    
    public $staticconstantCouldNotBe = self::NONE; // Assigning another constant ? 

    public $intCouldBe = 1; // right

    public $undefinedCouldNotBe; // right
    
}

?>