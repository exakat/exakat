<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extrunkit extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extrunkit01()  { $this->generic_test('Extensions_Extrunkit.01'); }
}
?>