<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SpecialIntegers extends Analyzer {
    /* 1 methods */

    public function testType_SpecialIntegers01()  { $this->generic_test('Type_SpecialIntegers.01'); }
}
?>