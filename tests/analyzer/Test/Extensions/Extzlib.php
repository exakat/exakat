<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extzlib extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extzlib01()  { $this->generic_test('Extensions_Extzlib.01'); }
}
?>