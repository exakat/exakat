<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extfam extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extfam01()  { $this->generic_test('Extensions/Extfam.01'); }
}
?>