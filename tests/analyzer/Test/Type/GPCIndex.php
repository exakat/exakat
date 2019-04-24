<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class GPCIndex extends Analyzer {
    /* 1 methods */

    public function testType_GPCIndex01()  { $this->generic_test('Type/GPCIndex.01'); }
}
?>