<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extzbarcode extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extzbarcode01()  { $this->generic_test('Extensions/Extzbarcode.01'); }
}
?>