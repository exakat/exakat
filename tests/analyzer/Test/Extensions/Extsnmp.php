<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extsnmp extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extsnmp01()  { $this->generic_test('Extensions_Extsnmp.01'); }
}
?>