<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extrar extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extrar01()  { $this->generic_test('Extensions/Extrar.01'); }
}
?>