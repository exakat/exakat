<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Pack extends Analyzer {
    /* 1 methods */

    public function testType_Pack01()  { $this->generic_test('Type/Pack.01'); }
}
?>