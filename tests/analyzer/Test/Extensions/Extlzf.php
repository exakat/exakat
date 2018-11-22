<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extlzf extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extlzf01()  { $this->generic_test('Extensions/Extlzf.01'); }
}
?>