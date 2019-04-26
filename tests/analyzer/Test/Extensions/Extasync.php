<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extasync extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extasync01()  { $this->generic_test('Extensions/Extasync.01'); }
}
?>