<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extob extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extob01()  { $this->generic_test('Extensions_Extob.01'); }
}
?>