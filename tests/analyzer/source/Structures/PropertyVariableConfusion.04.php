<?php

class x {
    protected $property = 1, $propertyy = 3;
    protected $property2 = 2, $property2y = 2;
    protected $property3, $property3y;
    protected $property4, $property4y;
    
    function y() {
        $property = $this->property;
        $property3 = $this->property3;

        $propertyy = $this->propertyy;
        $property3y = $this->property3y;
    }

    function y2($property2, $property4, $property2y, $property4y) {
        $this->property2 = $property2;
        $this->property4 = $property4;

        $this->property2y = $property2y;
        $this->property4y = $property4y;
    }

}
?>