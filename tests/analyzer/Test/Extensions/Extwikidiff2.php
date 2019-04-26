<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extwikidiff2 extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extwikidiff201()  { $this->generic_test('Extensions/Extwikidiff2.01'); }
}
?>