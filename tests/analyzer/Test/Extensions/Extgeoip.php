<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extgeoip extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extgeoip01()  { $this->generic_test('Extensions/Extgeoip.01'); }
}
?>