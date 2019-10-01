<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Binary extends Analyzer {
    /* 4 methods */

    public function testType_Binary01()  { $this->generic_test('Type_Binary.01'); }
    public function testType_Binary02()  { $this->generic_test('Type_Binary.02'); }
    public function testType_Binary03()  { $this->generic_test('Type/Binary.03'); }
    public function testType_Binary04()  { $this->generic_test('Type/Binary.04'); }
}
?>