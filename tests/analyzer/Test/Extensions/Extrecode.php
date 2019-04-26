<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extrecode extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extrecode01()  { $this->generic_test('Extensions_Extrecode.01'); }
}
?>