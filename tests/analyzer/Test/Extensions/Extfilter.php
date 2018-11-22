<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extfilter extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extfilter01()  { $this->generic_test('Extensions_Extfilter.01'); }
}
?>