<?php

class x {
    protected $property = 1;
    protected $property2 = 2;
    
    function y() {
        $property = $this->property;
    }

    function y2($property2) {
        $this->property2 = $property2;
    }

}
?>