<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extoci8 extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extoci801()  { $this->generic_test('Extensions_Extoci8.01'); }
}
?>