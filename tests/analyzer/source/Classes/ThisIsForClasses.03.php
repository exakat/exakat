<?php

class x {
    private $ok = 1;
    private $ok2 = 2;
    private $ok3 = 3;
    
    function z() {
        echo $this->ok;

        // $this goes into closure
        $x2 = function($a) { echo $this->ok2; };
        $x2(4);

        // $this doesn't go into functions
        function x3 ($a) { echo $this->ok3; };
        x3(5);
//        $x3 = function($a) use ($this) { echo $this->ok3; };
    }
}

(new x) ->z();

?>