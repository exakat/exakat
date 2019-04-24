<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extstats extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extstats01()  { $this->generic_test('Extensions/Extstats.01'); }
}
?>