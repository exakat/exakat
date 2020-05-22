<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Pack extends Analyzer {
    /* 2 methods */

    public function testType_Pack01()  { $this->generic_test('Type/Pack.01'); }
    public function testType_Pack02()  { $this->generic_test('Type/Pack.02'); }
}
?>