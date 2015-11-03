<?php
class x {
    public $used = 1;
    public $unused = 2;
    public $usedInside = 3;
    public static $usedButStatic = 4;
    
    function y() {
        $this->usedInside = 3;
    }
}

$x->used = 4;
$x->usedButStatic = 3;
\x::$usedButStatic = 5;

?>