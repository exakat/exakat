<?php

class x {
    private $optionalComparison = null;
    private $optionalComparison2 = null;
    private $optionalComparison3 = null;
    private $optionalComparison4 = null;

    private $optionalComparisonArray = array();
    private $optionalComparisonArray2 = array();
    private $optionalComparisonArray3 = [];
    private $optionalComparisonArray4 = [];

    function foo() {
        if ($this->optionalComparison === null) {}
        if (empty($this->optionalComparison2)) {}
        if (isset($this->optionalComparison3)) {}
        if (is_null($this->optionalComparison4)) {}

        if ($this->optionalComparisonArray === null) {}
        if (empty($this->optionalComparison2Array)) {}
        if (isset($this->optionalComparison3Array)) {}
        if (is_null($this->optionalComparison4Array)) {}

        if ($a->optionalAComparison === null) {}
        if (empty($a->optionalAComparison2)) {}
        if (isset($a->optionalAComparison3)) {}
        if (is_null($a->optionalAComparison4)) {}
    }    
}

?>