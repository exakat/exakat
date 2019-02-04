<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extpcov extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extpcov01()  { $this->generic_test('Extensions/Extpcov.01'); }
}
?>