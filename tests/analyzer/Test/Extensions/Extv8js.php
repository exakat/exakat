<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extv8js extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extv8js01()  { $this->generic_test('Extensions/Extv8js.01'); }
}
?>