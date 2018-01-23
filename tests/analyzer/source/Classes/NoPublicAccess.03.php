<?php
class x {
    public $used = 1;
    public $uSed = 1;
    public $uSEd = 1;
    public $unused = 2;
    public $unUsed = 2;
    public $unUSed = 2;
    public $usedInside = 3;
    public $used_inside_various_cases = 3;
    public $used_INSIDE_various_cases = 3;
    public static $usedButStatic = 4;
    
    function y() {
        $this->usedInside = 3;
        $this->used_inside_various_cases = 5;
        $this->used_INSIDE_various_cases = 6;
    }
}

$x->used = 4;
$x->uSed = 4;
$x->uSEd = 4;
$x->usedButStatic = 3;
\x::$usedButStatic = 5;

?>