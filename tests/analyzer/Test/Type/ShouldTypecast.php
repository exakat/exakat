<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldTypecast extends Analyzer {
    /* 2 methods */

    public function testType_ShouldTypecast01()  { $this->generic_test('Type_ShouldTypecast.01'); }
    public function testType_ShouldTypecast02()  { $this->generic_test('Type/ShouldTypecast.02'); }
}
?>