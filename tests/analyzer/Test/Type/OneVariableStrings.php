<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class OneVariableStrings extends Analyzer {
    /* 4 methods */

    public function testType_OneVariableStrings01()  { $this->generic_test('Type_OneVariableStrings.01'); }
    public function testType_OneVariableStrings02()  { $this->generic_test('Type_OneVariableStrings.02'); }
    public function testType_OneVariableStrings03()  { $this->generic_test('Type_OneVariableStrings.03'); }
    public function testType_OneVariableStrings04()  { $this->generic_test('Type/OneVariableStrings.04'); }
}
?>