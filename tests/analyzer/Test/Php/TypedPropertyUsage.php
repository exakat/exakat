<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TypedPropertyUsage extends Analyzer {
    /* 2 methods */

    public function testPhp_TypedPropertyUsage01()  { $this->generic_test('Php/TypedPropertyUsage.01'); }
    public function testPhp_TypedPropertyUsage02()  { $this->generic_test('Php/TypedPropertyUsage.02'); }
}
?>