<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AvoidOptionalProperties extends Analyzer {
    /* 4 methods */

    public function testClasses_AvoidOptionalProperties01()  { $this->generic_test('Classes/AvoidOptionalProperties.01'); }
    public function testClasses_AvoidOptionalProperties02()  { $this->generic_test('Classes/AvoidOptionalProperties.02'); }
    public function testClasses_AvoidOptionalProperties03()  { $this->generic_test('Classes/AvoidOptionalProperties.03'); }
    public function testClasses_AvoidOptionalProperties04()  { $this->generic_test('Classes/AvoidOptionalProperties.04'); }
}
?>