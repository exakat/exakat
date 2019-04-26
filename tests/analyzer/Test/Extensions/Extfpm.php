<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extfpm extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extfpm01()  { $this->generic_test('Extensions_Extfpm.01'); }
}
?>