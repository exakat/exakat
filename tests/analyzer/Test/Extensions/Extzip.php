<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extzip extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extzip01()  { $this->generic_test('Extensions_Extzip.01'); }
}
?>