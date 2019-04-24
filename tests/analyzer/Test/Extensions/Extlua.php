<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extlua extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extlua01()  { $this->generic_test('Extensions/Extlua.01'); }
}
?>