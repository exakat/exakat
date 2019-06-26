<?php

class x {
    function foo($a, $b) { 
        $this->foo($a, $b); 
    }
    
    function foo2($a, $b) { 
        if ($a > 10) {
            return;
        }
        $this->foo2($a, $b); 
    }

    function foo3($a, $b) { 
        self::foo3($a, $b); 
    }

    function foo4($a, $b) { 
        $a->foo4($a, $b); 
    }
}
?>