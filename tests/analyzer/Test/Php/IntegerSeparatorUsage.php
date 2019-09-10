<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IntegerSeparatorUsage extends Analyzer {
    /* 2 methods */

    public function testPhp_IntegerSeparatorUsage01()  { $this->generic_test('Php/IntegerSeparatorUsage.01'); }
    public function testPhp_IntegerSeparatorUsage02()  { $this->generic_test('Php/IntegerSeparatorUsage.02'); }
}
?>