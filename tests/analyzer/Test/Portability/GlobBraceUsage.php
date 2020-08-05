<?php

namespace Test\Portability;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class GlobBraceUsage extends Analyzer {
    /* 1 methods */

    public function testPortability_GlobBraceUsage01()  { $this->generic_test('Portability/GlobBraceUsage.01'); }
}
?>