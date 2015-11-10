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

    function y3($property5 = 3, $property6 = 2) {
        $this->property5 = $property5;
        $this->property6 = $property6;
    }

    function y4(Stdclass $property7 = null, Stdclass $property8 = null) {
        $this->property7 = $property7;
        $this->property8 = $property8;
    }

}
?>