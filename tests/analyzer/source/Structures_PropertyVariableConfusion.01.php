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

    function y2($property2, $property4) {
        $this->property2 = $property2;
        $this->property4 = $property4;
    }

}
?>