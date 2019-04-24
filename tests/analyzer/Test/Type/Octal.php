<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Octal extends Analyzer {
    /* 3 methods */

    public function testType_Octal01()  { $this->generic_test('Type_Octal.01'); }
    public function testType_Octal02()  { $this->generic_test('Type_Octal.02'); }
    public function testType_Octal03()  { $this->generic_test('Type/Octal.03'); }
}
?>