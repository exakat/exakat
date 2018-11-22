<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extlua extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extlua01()  { $this->generic_test('Extensions/Extlua.01'); }
}
?>