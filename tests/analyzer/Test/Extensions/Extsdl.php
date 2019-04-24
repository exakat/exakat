<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extsdl extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extsdl01()  { $this->generic_test('Extensions/Extsdl.01'); }
}
?>