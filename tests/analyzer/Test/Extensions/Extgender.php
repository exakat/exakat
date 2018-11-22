<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extgender extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extgender01()  { $this->generic_test('Extensions/Extgender.01'); }
}
?>