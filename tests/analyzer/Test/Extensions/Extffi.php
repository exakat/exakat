<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extffi extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extffi01()  { $this->generic_test('Extensions/Extffi.01'); }
}
?>