<?php

$expected     = array('$this->optionalComparison === null',
                      'empty($this->optionalComparison2)',
                      'isset($this->optionalComparison3)',
                      'is_null($this->optionalComparison4)',
);

$expected_not = array('$a->optionalAComparison === null',
                      'empty($a->optionalAComparison2)',
                      'isset($a->optionalAComparison3)',
                      'is_null($a->optionalAComparison4)',
                      '$this->optionalComparisonArray === null',
                      'empty($this->optionalComparisonArray2)',
                      'isset($this->optionalComparisonArray3)',
                      'is_null($this->optionalComparisonArray4)',);

?>