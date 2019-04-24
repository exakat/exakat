<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extssh2 extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extssh201()  { $this->generic_test('Extensions_Extssh2.01'); }
}
?>