<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extodbc extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extodbc01()  { $this->generic_test('Extensions_Extodbc.01'); }
}
?>