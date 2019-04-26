<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class YieldUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_YieldUsage01()  { $this->generic_test('Php/YieldUsage.01'); }
}
?>