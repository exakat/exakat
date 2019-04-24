<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extstandard extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extstandard01()  { $this->generic_test('Extensions_Extstandard.01'); }
}
?>