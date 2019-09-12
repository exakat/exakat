<?php

class x {
    private $p = 2;
    private $pp;
    private $ppp = null;
    
    function foo($a) {
        static $s = 1;
        global $g;
        
        $a = 1;
        $b = 2;
        $this->pp = 3;
        $this->p = 3;
        
        $g = 2;
        $s = 4;
    }
}
?>