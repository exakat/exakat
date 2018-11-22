<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extmssql extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extmssql01()  { $this->generic_test('Extensions_Extmssql.01'); }
}
?>