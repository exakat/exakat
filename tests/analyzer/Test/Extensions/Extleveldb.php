<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extleveldb extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extleveldb01()  { $this->generic_test('Extensions/Extleveldb.01'); }
}
?>