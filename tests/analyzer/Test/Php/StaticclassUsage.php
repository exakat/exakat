<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StaticclassUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_StaticclassUsage01()  { $this->generic_test('Php/StaticclassUsage.01'); }
}
?>