<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Exthash extends Analyzer {
    /* 1 methods */

    public function testExtensions_Exthash01()  { $this->generic_test('Extensions_Exthash.01'); }
}
?>