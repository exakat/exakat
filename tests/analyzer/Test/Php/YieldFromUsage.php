<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class YieldFromUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_YieldFromUsage01()  { $this->generic_test('Php/YieldFromUsage.01'); }
}
?>