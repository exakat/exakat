<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Exthttp extends Analyzer {
    /* 1 methods */

    public function testExtensions_Exthttp01()  { $this->generic_test('Extensions/Exthttp.01'); }
}
?>