<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extyis extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extyis01()  { $this->generic_test('Extensions_Extyis.01'); }
}
?>