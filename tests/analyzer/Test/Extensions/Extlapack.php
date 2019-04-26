<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extlapack extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extlapack01()  { $this->generic_test('Extensions/Extlapack.01'); }
}
?>