<?php

class x {
    function __constructor() {
        $this->x = function() { return 1; };
    }

    function __destructor() {
        $this->x = function() { return 2; };
    }
    
    function usableReturn() {
        $this->x = function() { return 3; };
    }
}


?>