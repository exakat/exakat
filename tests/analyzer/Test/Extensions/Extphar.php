<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extphar extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extphar01()  { $this->generic_test('Extensions_Extphar.01'); }
}
?>