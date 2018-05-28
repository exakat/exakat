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

    function y3($property5y3 = 3, $property6y3 = 2) {
        $this->property5 = $property5y3;
        $this->property6 = $property6y3;
    }

    function y4(Stdclass $property7y4 = null, Stdclass $property8y4 = null) {
        $this->property7 = $property7y4;
        $this->property8 = $property8y4;
    }

}
?>