<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AvoidOptionalProperties extends Analyzer {
    /* 2 methods */

    public function testClasses_AvoidOptionalProperties01()  { $this->generic_test('Classes/AvoidOptionalProperties.01'); }
    public function testClasses_AvoidOptionalProperties02()  { $this->generic_test('Classes/AvoidOptionalProperties.02'); }
}
?>