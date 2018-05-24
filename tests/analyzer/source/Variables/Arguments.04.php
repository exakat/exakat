<?php

class x {
    protected $property = 1;
    protected $property2 = 2;
    protected $property3;
    protected $property4;
    
    function y() {
        $property = $this->property;
        $property3 = $this->property3;
    }

    function y2($property2y2, $property4y2) {
        $this->property2 = $property2y2;
        $this->property4 = $property4y2;
    }

}
?>