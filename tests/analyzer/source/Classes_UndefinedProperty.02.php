<?php

class x {
    protected $property = 1, $property2, $property3 = array();
    
    function y() {
        $this->property = 4;
        $this->property2 = 5;
        $this->property3 = 6;
        $this->$dynamicProperty = 7;
        $this->undefinedProperty = 8;
    }
    
}
?>